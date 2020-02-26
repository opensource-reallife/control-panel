<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketAnswer;
use Illuminate\Http\Request;
use App\Models\TicketCategory;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $state = request()->get('state');
        if(!in_array($state, ['open', 'both', 'closed'])) {
            $state = 'open';
        }

        $tickets = auth()->user()->tickets()->with('user', 'assignee', 'category', 'resolver');

        if(auth()->user()->Rank >= 1) {
            $tickets = Ticket::with('user', 'assignee', 'category', 'resolver');
        }

        switch($state)
        {
            case 'open':
                $tickets->where('State', Ticket::TICKET_STATE_OPEN);
                break;
            case 'closed':
                $tickets->where('State', Ticket::TICKET_STATE_CLOSED);
                break;
        }

        $tickets = $tickets->get();

        $response = [];

        foreach($tickets as $ticket) {
            $entry = [
                'Id' => $ticket->Id,
                'UserId' => $ticket->UserId,
                'User' => $ticket->user->Name,
                'AssigneeId' => $ticket->AssigneeId,
                'AssignedRank' => $ticket->AssignedRank,
                'CategoryId' => $ticket->CategoryId,
                'Category' => $ticket->category->Title,
                'Title' => $ticket->Title,
                'State' => $ticket->State,
                'StateText' => $ticket->State === Ticket::TICKET_STATE_OPEN ? 'Offen' : 'Geschlossen',
                'ResolvedBy' => $ticket->ResolvedBy,
                'LastResponseAt' => $ticket->LastResponseAt->format('d.m.Y H:i:s'),
                'CreatedAt' => $ticket->CreatedAt->format('d.m.Y H:i:s'),
                'ResolvedAt' => $ticket->ResolvedAt ? $ticket->ResolvedAt->format('d.m.Y H:i:s') : null,
            ];

            if($ticket->assignee) {
                $entry['Assignee'] = $ticket->assignee->Name;
            }

            if($ticket->resolver) {
                $entry['Resolver'] = $ticket->assignee->Name;
            }


            array_push($response, (object)$entry);
        }

        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ticket = new Ticket();
        $ticket->UserId = auth()->user()->Id;
        $ticket->CategoryId = $request->get('category');
        $ticket->Title = $request->get('title');
        $ticket->State = Ticket::TICKET_STATE_OPEN;
        $ticket->save();

        $ticket->users()->attach(auth()->user(), ['JoinedAt' => new Carbon()]);

        $fields = $request->get('fields');

        if(!empty($fields)) {
            $category = TicketCategory::with('fields')->find($ticket->CategoryId);

            $text = [];

            foreach($fields as $key => $value) {
                foreach($category->fields as $field) {
                    if('field' . $field->Id === $key) {
                        array_push($text, $field->Name . ': ' . $value);
                        break;
                    }
                }
            }

            if(!empty($text)) {
                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = auth()->user()->Id;
                $answer->MessageType = 1;
                $answer->Message = implode(chr(0x0A), $text);
                $answer->save();
            }
        }


        $answer = new TicketAnswer();
        $answer->TicketId = $ticket->Id;
        $answer->UserId = auth()->user()->Id;
        $answer->MessageType = 0;
        $answer->Message = $request->get('message');
        $answer->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        abort_unless(auth()->user()->can('show', $ticket), 403);

        $entry = [
            'Id' => $ticket->Id,
            'UserId' => $ticket->UserId,
            'User' => $ticket->user->Name,
            'AssigneeId' => $ticket->AssigneeId,
            'AssignedRank' => $ticket->AssignedRank,
            'CategoryId' => $ticket->CategoryId,
            'Category' => $ticket->category->Title,
            'Title' => $ticket->Title,
            'State' => $ticket->State,
            'StateText' => $ticket->State === Ticket::TICKET_STATE_OPEN ? 'Offen' : 'Geschlossen',
            'ResolvedBy' => $ticket->ResolvedBy,
            'LastResponseAt' => $ticket->LastResponseAt->format('d.m.Y H:i:s'),
            'CreatedAt' => $ticket->CreatedAt->format('d.m.Y H:i:s'),
            'ResolvedAt' => $ticket->ResolvedAt ? $ticket->ResolvedAt->format('d.m.Y H:i:s') : null,
        ];

        if($ticket->assignee) {
            $entry['Assignee'] = $ticket->assignee->Name;
        }

        if($ticket->resolver) {
            $entry['Resolver'] = $ticket->assignee->Name;
        }

        if($ticket->users) {
            $entry['users'] = [];
            foreach($ticket->users as $user) {
                array_push($entry['users'], [
                    'UserId' => $user->Id,
                    'Name' => $user->Name,
                    'JoinedAt' => (new Carbon($user->pivot->JoinedAt))->format('d.m.Y H:i:s'),
                    'LeftAt' => $user->pivot->LeftAt ? (new Carbon($user->pivot->LeftAt))->format('d.m.Y H:i:s') : null,
                ]);
            }
        }

        $entry['answers'] = [];
        $answers = $ticket->answers()->with('user')->get();

        foreach($answers as $answer) {
            array_push($entry['answers'], [
                'Id' => $answer->Id,
                'UserId' => $answer->UserId,
                'User' => $answer->user->Name,
                'MessageType' => $answer->MessageType,
                'Message' => $answer->Message,
                'IsMyMessage' => $answer->UserId === auth()->user()->Id,
                'CreatedAt' => $answer->CreatedAt->format('d.m.Y H:i:s'),
            ]);
        }

        return $entry;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        abort_unless(auth()->user()->can('update', $ticket), 403);

        $type = $request->get('type');
        $userId = auth()->user()->Id;

        switch($type)
        {
            case 'addMessage':
                if($ticket->State === Ticket::TICKET_STATE_CLOSED) {
                    return;
                }
                if (!$ticket->users->contains($userId)) {
                    $ticket->users()->attach($userId, ['JoinedAt' => new Carbon()]);
                    $ticket->save();

                    $answer = new TicketAnswer();
                    $answer->TicketId = $ticket->Id;
                    $answer->UserId = $userId;
                    $answer->MessageType = 1;
                    $answer->Message = sprintf("%s ist dem Ticket beigetreten!", auth()->user()->Name);
                    $answer->save();
                }
                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = $userId;
                $answer->MessageType = 0;
                $answer->Message = $request->get('message');
                $answer->save();
                break;
            case 'close':
                $ticket->State = Ticket::TICKET_STATE_CLOSED;
                $ticket->save();

                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = $userId;
                $answer->MessageType = 1;
                $answer->Message = 'Ticket wurde geschlossen';
                $answer->save();
                break;
            case 'addUser':
                if ($ticket->users->contains($request->get('newUserId'))) {
                    return;
                }

                $ticket->users()->attach($request->get('newUserId'), ['JoinedAt' => new Carbon()]);
                $ticket->save();

                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = $userId;
                $answer->MessageType = 1;
                $answer->Message = sprintf("%s hat %s zum Ticket hinzugefügt!", auth()->user()->Name, User::find($request->get('newUserId'))->Name);
                $answer->save();
                break;
            case 'removeUser':
                if (!$ticket->users->contains($request->get('removeUserId'))) {
                    return;
                }

                $ticket->users()->updateExistingPivot($request->get('removeUserId'), ['LeftAt' => new Carbon()]);
                $ticket->save();

                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = $userId;
                $answer->MessageType = 1;
                $answer->Message = sprintf("%s hat %s aus dem Ticket entfernt!", auth()->user()->Name, User::find($request->get('removeUserId'))->Name);
                $answer->save();
                break;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
