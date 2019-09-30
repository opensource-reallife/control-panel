@extends('layouts.app')

@section('content')
    <div class="flex items-center">
        <div class="w-full ml-2 mr-2 md:w-2/3 md:mx-auto">
            <h2 class="text-3xl mb-8">{{ $faction->Name }}</h2>
            <div class="flex flex-row">
                <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md w-1/3">

                    <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                        Mitglieder
                    </div>

                    <table class="table w-full">
                        <tr>
                            <th>Name</th>
                            <th>Rang</th>
                            <th>Aktivität</th>
                        </tr>
                        @foreach($faction->members()->with('user')->orderBy('FactionRank', 'DESC')->get() as $character)
                            <tr>
                                <td><a href="{{ route('users.show', [$character->Id]) }}">{{ $character->user->Name }}</a></td>
                                <td>{{ $character->FactionRank }}</td>
                                <td>{{ number_format($character->getWeekActivity() / 60, 1, ',', ' ') }} h</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="flex flex-col ml-4 w-2/3">

                    <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md w-full">

                        <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                            Aktivität
                        </div>

                        <chart-component :chartdata="{{ json_encode($faction->getActivity(true)) }}" :options="{{ json_encode(['scales' => ['yAxes' => [['ticks' => ['beginAtZero' => true, 'suggestedMax' => 8]]]]]) }}"></chart-component>
                    </div>


                    <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md mt-4 w-full">

                        <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                            Logs
                        </div>

                        <table class="table w-full">
                            <tr>
                                <th>User</th>
                                <th>Beschreibung</th>
                            </tr>
                            @foreach($faction->logs()->orderBy('Timestamp', 'DESC')->limit(100)->with('user')->with('user.user')->get() as $log)
                                <tr>
                                    <td><a href="{{ route('users.show', [$log->UserId]) }}">{{ $log->user->user->Name }}</a></td>
                                    <td>{{ $log->Description }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
