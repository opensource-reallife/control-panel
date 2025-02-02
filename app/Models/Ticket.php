<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    const TICKET_STATE_OPEN = 'Open';
    const TICKET_STATE_CLOSED = 'Closed';

    use SoftDeletes;
    const DELETED_AT = 'DeletedAt';
    const UPDATED_AT = 'LastResponseAt';
    const CREATED_AT = 'CreatedAt';
    protected $primaryKey = 'Id';
    protected $dates = ['DeletedAt', 'LastResponseAt', 'CreatedAt', 'ResolvedAt'];


    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function assignee()
    {
        return $this->hasOne(User::class, 'Id', 'AssigneeId');
    }

    public function resolver()
    {
        return $this->hasOne(User::class, 'Id', 'ResolvedBy');
    }

    public function category()
    {
        return $this->hasOne(TicketCategory::class, 'Id', 'CategoryId');
    }

    public function answers()
    {
        return $this->hasMany(TicketAnswer::class, 'TicketId', 'Id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'ticket_users', 'TicketId', 'UserId')->withPivot('JoinedAt', 'LeftAt', 'IsAdmin');
    }

    public function getApiResponse($leftAt = null)
    {
        $entry = [
            'Id' => $this->Id,
            'UserId' => $this->UserId,
            'User' => $this->user->Name,
            'AssigneeId' => $this->AssigneeId,
            'AssignedRank' => $this->AssignedRank,
            'CategoryId' => $this->CategoryId,
            'Category' => $this->category->Title,
            'Title' => $this->Title,
            'State' => $this->State,
            'StateText' => $this->State === Ticket::TICKET_STATE_OPEN ? 'Offen' : 'Geschlossen',
            'ResolvedBy' => $this->ResolvedBy,
            'LastResponseAt' => $this->LastResponseAt->format('d.m.Y H:i:s'),
            'CreatedAt' => $this->CreatedAt->format('d.m.Y H:i:s'),
            'ResolvedAt' => $this->ResolvedAt ? $this->ResolvedAt->format('d.m.Y H:i:s') : null,
            'settings' => [
                'display' => auth()->user()->TicketDisplay
            ]
        ];

        if($this->assignee) {
            $entry['Assignee'] = $this->assignee->Name;
        }

        if($this->resolver) {
            $entry['Resolver'] = $this->resolver->Name;
        }

        $isAdmin = [];

        if($this->users) {
            $entry['users'] = [];
            foreach($this->users as $user) {
                $isAdmin[$user->Id] = $user->pivot->IsAdmin === 1;
                array_push($entry['users'], [
                    'UserId' => $user->Id,
                    'Name' => $user->Name,
                    'IsAdmin' => $user->pivot->IsAdmin === 1,
                    'JoinedAt' => (new Carbon($user->pivot->JoinedAt))->format('d.m.Y H:i:s'),
                    'LeftAt' => $user->pivot->LeftAt ? (new Carbon($user->pivot->LeftAt))->format('d.m.Y H:i:s') : null,
                ]);
            }
        }

        $entry['answers'] = [];
        $answers = $this->answers()->with('user')->get();
        $entry['AnswerCount'] = 0;

        foreach($answers as $answer) {
            if($answer->MessageType === 0) {
                $entry['AnswerCount']++;
            }

            if ($answer->MessageType === 2) {
                if (auth()->user()->Rank === 0) {
                    continue;
                }
            }

            if (auth()->user()->Rank === 0 && $leftAt !== null && $answer->CreatedAt > $leftAt)
            {
                continue;
            }

            array_push($entry['answers'], [
                'Id' => $answer->Id,
                'UserId' => $answer->UserId,
                'User' => $answer->user ? $answer->user->Name : __('Unbekannt'),
                'MessageType' => $answer->MessageType,
                'Message' => $answer->Message,
                'IsAdmin' => isset($isAdmin[$answer->UserId]) ? $isAdmin[$answer->UserId] : false,
                'CreatedAt' => $answer->CreatedAt->format('d.m.Y H:i:s'),
            ]);
        }

        return $entry;
    }
}
