@php
    $vehicleDamage = \App\Models\Logs\VehicleDamage::with(['user', 'target'])->orderBy('Id', 'DESC')->paginate(request()->get('limit') ?? 25);
@endphp
<table class="table table-hover table-sm table-responsive-sm tw-full">
    <tr>
        <th>{{ __('Id') }}</th>
        <th>{{ __('Start') }}</th>
        <th>{{ __('Ende') }}</th>
        <th>{{ __('Schütze') }}</th>
        <th>{{ __('FahrzeugId') }}</th>
        <th>{{ __('Besitzer') }}</th>
        <th>{{ __('Waffe') }}</th>
        <th>{{ __('Schaden') }}</th>
        <th>{{ __('Reifen') }}</th>
        <th>{{ __('Position') }}</th>
    </tr>
    @foreach($vehicleDamage as $entry)
        <tr>
            <td>{{ $entry->Id }}</td>
            <td>{{ $entry->StartDate }}</td>
            <td>{{ $entry->Date }}</td>
            <td>
                @if($entry->user)<a href="{{ route('users.show', [$entry->UserId]) }}">{{ $entry->user->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->UserId }})@endif
            </td>
            <td>{{ $entry->VehicleId }}</td>
            <td>
                @if($entry->target)
                    @if($entry->target->OwnerType === 1)
                        <a href="{{ route('users.show', [$entry->target->OwnerId]) }}">{{ App\Models\User::find($entry->target->OwnerId)->Name }}</a>
                    @elseif($entry->target->OwnerType === 2)
                        <a href="{{ route('factions.show', [$entry->target->OwnerId]) }}">{{ App\Models\Faction::find($entry->target->OwnerId)->Name }}</a>
                    @elseif($entry->target->OwnerType === 3)
                        <a href="{{ route('companies.show', [$entry->target->OwnerId]) }}">{{ App\Models\Company::find($entry->target->OwnerId)->Name }}</a>
                    @elseif($entry->target->OwnerType === 4)
                        <a href="{{ route('groups.show', [$entry->target->OwnerId]) }}">{{ App\Models\Group::find($entry->target->OwnerId)->Name }}</a>
                    @endif
                @endif
            </td>
            {{-- <td>
                @if($entry->target)<a href="{{ route('users.show', [$entry->TargetId]) }}">{{ $entry->target->Name }}</a>@else{{ 'Unknown' }} (ID: {{ $entry->TargetId }})@endif
            </td> --}} 
            <td>{{ $entry->Weapon }}</td>
            <td>{{ $entry->TireId ? "-" : $entry->TotalLoss }}</td>
            <td>{{ $entry->TireId ?? "-" }}</td>
            <td>{{ $entry->Location }}</td>
        </tr>
    @endforeach
</table>
{{ $vehicleDamage->links() }}
