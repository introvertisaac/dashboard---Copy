<div class="section-menu-left" style="display: flex; flex-direction: column; height: 100vh;">
    <div class="box-logo">
        <a href="{{route('index')}}" id="site-logo-inner">
            <img class="" id="logo_header" alt="" src="{{asset('logo-main.png')}}"
                 data-light="{{asset('logo-main.png')}}" width="154px" height="52px"
                 data-dark="{{asset('logo-white.png')}}">
        </a>

        <div class="button-show-hide">
            <i class="icon-menu-left"></i>
        </div>
    </div>
    <div class="center" style="flex-grow: 1; display: flex; flex-direction: column;">
        <div class="center-item" style="flex-grow: 1;">
            <ul class="menu-list" style="display: flex; flex-direction: column; height: 100%;">
                <li class="menu-item  menu_dashboard">
                    <a href="{{route('dashboard')}}" class="menu_dashboard">
                        <div class="icon"><i class="icon-grid"></i></div>
                        <div class="text">Dashboard</div>
                    </a>
                </li>

                @can('perform_searches')
                    <li class="menu-item menu_checks">
                        <a href="{{route('checks')}}" class="menu_checks">
                            <div class="icon"><i class="icon-file"></i></div>
                            <div class="text">Verifications</div>
                        </a>
                    </li>
                    <li class="menu-item d-none">
                        <a href="{{route('dashboard')}}">
                            <div class="icon"><i class="icon-code"></i></div>
                            <div class="text">Transaction APIs</div>
                        </a>
                    </li>
                @endcan

                @canany(['create_customers','manage_customers','view_customers'])
                    <li class="menu-item menu_customers">
                        <a href="{{route('customers')}}" class="menu_customers">
                            <div class="icon"><i class="icon-user"></i></div>
                            <div class="text">Customers</div>
                        </a>
                    </li>
                @endcanany


                @canany(['create_users','manage_users'])
                    <li class="menu-item menu_users">
                        <a href="{{route('users')}}" class="menu_users">
                            <div class="icon"><i class="icon-users"></i></div>
                            <div class="text">Portal Users</div>
                        </a>
                    </li>
                @endcanany


                <li class="menu-item has-children">
                    <a href="javascript:void(0);" class="menu-item-button">
                        <div class="icon"><i class="icon-bar-chart-2"></i></div>
                        <div class="text">Reports</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="{{route('customer.transactions')}}" class="">
                                <div class="text">Customer Transactions</div>
                            </a>
                        </li>
                      {{--  <li class="sub-menu-item">
                            <a href="#" class="">
                                <div class="text">Revenue Report</div>
                            </a>
                        </li>

                        <li class="sub-menu-item">
                            <a href="#" class="">
                                <div class="text">Topup History</div>
                            </a>
                        </li>--}}
                    </ul>
                </li>




                <li class="menu-item spacer" style="flex-grow: 1;"></li> <!-- Spacer to push items to bottom -->

                <li class="menu-item menu_settings">
                    <a class="menu_settings" href="{{route('settings')}}">
                        <div class="icon"><i class="icon-settings"></i></div>
                        <div class="text">Settings</div>
                    </a>
                </li>


                <li class="menu-item">
                    <a class="" target="_blank" href="{{route('scramble.docs.ui')}}">
                        <div class="icon"><i class="icon-code"></i></div>
                        <div class="text">API Docs</div>
                    </a>
                </li>


                <li class="menu-item">
                    <a href="{{route('logout')}}">
                        <div class="icon"><i class="icon-log-out"></i></div>
                        <div class="text">Logout</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
