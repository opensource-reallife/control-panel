@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('Wer ist online') }}
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Fraktion') }}</th>
                                <th scope="col">{{ __('Unternehmen') }}</th>
                                <th scope="col">{{ __('Gang/Firma') }}</th>
                                <th scope="col">{{ __('Spielzeit') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($players as $player)
                                <tr>
                                    <td><a href="{{ route('users.show', [$player->Id]) }}">{{ $player->Name }}</a></td>
                                    <td>@if($player->FactionId != 0)<a href="{{ route('factions.show', [$player->FactionId]) }}">{{ $player->FactionName }}</a>@else{{ $player->FactionName }}@endif</td>
                                    <td>@if($player->CompanyId != 0)<a href="{{ route('companies.show', [$player->CompanyId]) }}">{{ $player->CompanyName }}</a>@else{{ $player->CompanyName }}@endif</td>
                                    <td>@if($player->GroupId != 0)<a href="{{ route('groups.show', [$player->GroupId]) }}">{{ $player->GroupName }}</a>@else{{ $player->GroupName }}@endif</td>
                                    <td>@playTime($player->PlayTime)</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
