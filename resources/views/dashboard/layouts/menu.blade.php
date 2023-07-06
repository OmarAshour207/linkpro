<div class="mdk-drawer  js-mdk-drawer" id="default-drawer" data-align="start">
    <div class="mdk-drawer__content">
        <div class="sidebar sidebar-dark sidebar-left sidebar-p-t bg-dark" data-perfect-scrollbar>
            <div class="sidebar-heading">{{ __('Menu') }}</div>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item open">
                    <a class="sidebar-menu-button" data-toggle="collapse" href="#dashboards_menu" aria-expanded="true">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dvr</i>
                        <span class="sidebar-menu-text"> {{ __('Dashboard') }} </span>
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse show" id="dashboards_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="{{ route('dashboard.index') }}">
                                <i class="sidebar-menu-icon sidebar-menu-icon--left fa fa-chart-bar"></i>
                                <span class="sidebar-menu-text"> {{ __('admin.stat') }} </span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Services --}}
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="{{ route('services.index') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left fa fa-user-cog"></i>
                        <span class="sidebar-menu-text"> {{ __('Services') }} </span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="{{ route('users.index') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left fa fa-user-friends"></i>
                        <span class="sidebar-menu-text"> {{ __('Users') }} </span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="{{ route('companies.index') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left fa fa-city"></i>
                        <span class="sidebar-menu-text"> {{ __('Companies') }} </span>
                    </a>
                </li>


                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="{{ route('floors.index') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left fa fa-chevron-up"></i>
                        <span class="sidebar-menu-text"> {{ __('Floors') }} </span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="{{ route('paths.index') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left fa fa-compass"></i>
                        <span class="sidebar-menu-text"> {{ __('Paths') }} </span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="{{ route('offices.index') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left fa fa-table"></i>
                        <span class="sidebar-menu-text"> {{ __('Offices') }} </span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="{{ route('contents.index') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left fa fa-table"></i>
                        <span class="sidebar-menu-text"> {{ __('Offices Contents') }} </span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="{{ route('supplies.index') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left fa fa-table"></i>
                        <span class="sidebar-menu-text"> {{ __('Supplies') }} </span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" data-toggle="collapse" href="#dashboard_tickets">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left fa fa-globe"></i>
                        <span class="sidebar-menu-text"> {{ __('Tickets') }} </span>
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse" id="dashboard_tickets">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="{{ route('tickets', 'users') }}">
                                <i class="fa fa-flag"></i>
                                <span class="sidebar-menu-text"> {{ __('Users') }}</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="{{ route('tickets', 'companies') }}">
                                <i class="fa fa-flag"></i>
                                <span class="sidebar-menu-text"> {{ __('Companies') }}</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="{{ route('tickets', 'supplies') }}">
                                <i class="fa fa-flag"></i>
                                <span class="sidebar-menu-text"> {{ __('Supplies') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" data-toggle="collapse" href="#dashboard_language">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left fa fa-globe"></i>
                        <span class="sidebar-menu-text"> {{ __('Language') }} </span>
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse" id="dashboard_language">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="{{ route('language', 'ar') }}">
                                <i class="fa fa-flag"></i>
                                <span class="sidebar-menu-text"> {{ __('ar') }}</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="{{ route('language', 'en') }}">
                                <i class="fa fa-flag"></i>
                                <span class="sidebar-menu-text"> {{ __('English') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="{{ route('settings.index') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left fa fa-cog"></i>
                        <span class="sidebar-menu-text"> {{ __('Settings') }} </span>
                    </a>
                </li>


            </ul>
        </div>
    </div>
</div>
