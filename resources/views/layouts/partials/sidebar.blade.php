<div class="sidebar sidebar-dark sidebar-md  sidebar-fixed " id="sidebar"> <!-- c-sidebar-lg-show -->
    <div class="sidebar-brand">
        <a href="{{ '/' }}">
            <img class="sidebar-brand-full" src="/images/logo.png" width="118" height="46" alt="{{ config('app.name', 'Laravel') }}">
            <img class="sidebar-brand-narrow" src="/images/logo_small.png" width="46" height="46" alt="{{ config('app.name', 'Laravel') }}">
        </a>
    </div>
    <ul class="sidebar-nav" data-drodpown-accordion="true">
        <li class="nav-item">
            <a class="nav-link" href="https://forum.openreallife.net/">
                <i class="nav-icon fas fa-comments"></i>{{ __('Forum') }}
            </a>
        </li>
        @auth
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.search') }}">
                <i class="nav-icon fas fa-user"></i>{{ __('Benutzersuche') }}
            </a>
        </li>
        @endauth
        <li class="nav-item">
            <a class="nav-link" href="{{ route('factions.index') }}">
                <i class="nav-icon fas fa-user-friends"></i>{{ __('Fraktionen') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('companies.index') }}">
                <i class="nav-icon fas fa-user-friends"></i>{{ __('Unternehmen') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('groups.index') }}">
                <i class="nav-icon fas fa-user-friends"></i>{{ __('Gruppen') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('vehicles.index') }}"><span class="nav-icon fas fa-car"></span>{{ __('Fahrzeuge') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('tickets.index') }}">
                <i class="nav-icon fas fa-headset"></i>{{ __('Tickets') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('who.is.online') }}">
                <i class="nav-icon fas fa-globe-europe"></i>{{ __('Wer ist online') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('statistics') }}">
                <i class="nav-icon fas fa-list-ol"></i>{{ __('Statistiken') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="https://github.com/opensource-reallife/mta-gamemode/commits/release/production">
                <i class="nav-icon fas fa-code-branch"></i>{{ __('Commits') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('achievements') }}">
                <i class="nav-icon fas fa-trophy"></i>{{ __('Achievements') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('textures.index') }}">
                <i class="nav-icon fas fa-images"></i>{{ __('Texturen') }}
            </a>
        </li>
        @auth
            @if(auth()->user()->Rank >= 3)
                <li class="nav-group nav-item">
                    <a class="nav-link nav-group-toggle" href="#">
                        <i class="nav-icon fas fa-user-shield"></i>{{ __('Admin') }}
                    </a>
                    <ul class="nav-group-items">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard.index') }}"><i class="nav-icon fas fa-tachometer-alt"></i>Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.user.multiaccounts') }}"><i class="nav-icon fas fa-people-arrows"></i>Multiaccounts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.texture') }}"><i class="nav-icon fas fa-images"></i>Texturen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.logs.show') }}"><i class="nav-icon fas fa-file-alt"></i>Logs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.bans.index') }}"><i class="nav-icon fas fa-ban"></i>{{ __('Bans') }}</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.ip-hub.index') }}"><span class="nav-icon fas fa-network-wired"></span>{{ __('IP Hub') }}</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.houses.index') }}"><i class="nav-icon fas fa-home"></i>{{ __('Häuser') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.server.show') }}"><i class="nav-icon fas fa-server"></i>Server</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.polls.index') }}"><i class="nav-icon fas fa-poll"></i>{{ __('Abstimmung') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.tickets.index') }}"><i class="nav-icon fas fa-list-ol"></i>{{ __('Tickets') }}</a>
                        </li>
                        @if(auth()->user()->Rank >= 7)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.maps.index') }}"><i class="nav-icon fas fa-map"></i>Maps</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endauth
    </ul>
    <div class="sidebar-footer border-top d-flex" type="button" data-target="_parent" data-coreui-toggle="narrow">
        <button class="sidebar-toggler" type="button" data-target="_parent" data-coreui-toggle="narrow"></button>
    </div>
</div>
