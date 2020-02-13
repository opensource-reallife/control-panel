@can('logs', $user)
<div class="row">
    <div class="nav-tabs-boxed col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            @if(auth()->user()->Rank >= 3)
                <li class="nav-item"><a class="nav-link @if($log === 'punish'){{'active'}}@endif" href="{{ route('users.show.logs', [$user->Id, 'punish']) }}">{{ __('Strafen') }}</a></li>
                <li class="nav-item"><a class="nav-link @if($log === 'kills'){{'active'}}@endif" href="{{ route('users.show.logs', [$user->Id, 'kills']) }}">{{ __('Morde') }}</a></li>
                <li class="nav-item"><a class="nav-link @if($log === 'deaths'){{'active'}}@endif" href="{{ route('users.show.logs', [$user->Id, 'deaths']) }}">{{ __('Tode') }}</a></li>
                <li class="nav-item"><a class="nav-link @if($log === 'heal'){{'active'}}@endif" href="{{ route('users.show.logs', [$user->Id, 'heal']) }}">{{ __('Heilung') }}</a></li>
                <li class="nav-item"><a class="nav-link @if($log === 'damage'){{'active'}}@endif" href="{{ route('users.show.logs', [$user->Id, 'damage']) }}">{{ __('Schaden') }}</a></li>
            @endif
            <li class="nav-item"><a class="nav-link @if($log === 'money'){{'active'}}@endif" href="{{ route('users.show.logs', [$user->Id, 'money']) }}">{{ __('Geld') }}</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active">
                @if(auth()->user()->Rank >= 3)
                    @if($log === 'punish')
                        @include('users.partials.logs.punish')
                    @elseif($log === 'kills')
                        @include('users.partials.logs.kills')
                    @elseif($log === 'deaths')
                        @include('users.partials.logs.deaths')
                    @elseif($log === 'heal')
                        @include('users.partials.logs.heal')
                    @elseif($log === 'damage')
                        @include('users.partials.logs.damage')
                    @endif
                @endif
                @if($log === 'money')
                    @include('users.partials.logs.money')
                @endif
            </div>
        </div>
    </div>
</div>
@endcan
