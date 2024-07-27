<!DOCTYPE html>
<!--[if IE 8 ]>
<html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <!--[if IE]>
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
    <![endif]-->
    <title>@yield('title') - {{config('app.name')}}</title>


    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('font/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('icon/style.css') }}">

    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('favicon.png') }}">


</head>

<body class="body">

<div id="wrapper">

    <div id="page" class="">

        <div class="layout-wrap">

            <div id="preload" class="preload-container">
                <div class="preloading">
                    <span></span>
                </div>
            </div>


            @include('partials.menu')


            <div class="section-content-right">

                <div class="header-dashboard">
                    <div class="wrap">
                        <div class="header-left">
                            <a href="{{route('index')}}">
                                <img class="d-none" id="logo_header_mobile" alt="" src="{{asset('logo-main.png')}}"
                                     data-light="{{asset('logo-white.png')}}" data-dark="{{asset('logo-white.png')}}"
                                     {{--data-width="154px" data-height="52px" --}}data-retina="{{asset('logo-main.png')}}">
                            </a>
                            <div class="button-show-hide">
                                <i class="icon-menu-left"></i>
                            </div>
                            <form class="form-search flex-grow"></form>
                        </div>
                        <div class="header-grid">

                            <div class="header-item button-dark-light d-none">
                                <i class="icon-moon"></i>
                            </div>
                            <div class="popup-wrap message type-header">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-item">
                                                <span class="text-tiny hidden d-none"> </span>
                                                <i class="icon-bell"></i>
                                            </span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end has-content"
                                        aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <h6>Notifications</h6>
                                        </li>

                                        <li>
                                            <p>No notifications for now</p>
                                        </li>

                                        <li class="d-none">
                                            <div class="message-item item-4">
                                                <div class="image">
                                                    <i class="icon-noti-4"></i>
                                                </div>
                                                <div>
                                                    <div class="body-title-2">  Title<span> data</span>
                                                    </div>
                                                    <div class="text-tiny">Narration</div>
                                                </div>
                                            </div>
                                        </li>


                                        <li class="d-none"><a href="#" class="tf-button w-full">View all</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="popup-wrap user type-header">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-user wg-user">
                                                <span class="image">
                                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                      fill="#E76C21" width="34px" height="34px">
    <path d="M0 0h24v24H0z" fill="none"/>
    <path
            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
</svg>

                                                </span>
                                                <span class="flex flex-column">
                                                    <span class="body-title mb-2">{{user('name')}}</span>
                                                    <span class="text-tiny">{{customer()->name}}  <i
                                                                class="icon icon-chevron-down"></i></span>
                                                </span>
                                            </span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end has-content"
                                        aria-labelledby="dropdownMenuButton3">


                                        <li>
                                            <a href="{{route('settings')}}" class="user-item">
                                                <div class="icon">
                                                    <i class="icon-settings"></i>
                                                </div>
                                                <div class="body-title-2">Settings</div>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{route('logout')}}" class="user-item">
                                                <div class="icon">
                                                    <i class="icon-log-out"></i>
                                                </div>
                                                <div class="body-title-2">Log out</div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="main-content tf-bg-light-2">
                    <div class="main-content-inner">
                        <div class="main-content-wrap">

                            @include('partials.alerts')

                            @yield('content')


                        </div>
                    </div>

                    <div class="bottom-page">
                        <div class="body-text">Copyright © {{date('Y')}} {{config('app.name')}}</div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/apexcharts/apexcharts.js') }}"></script>
<script src="{{ asset('js/apexcharts/line-chart-5.js') }}"></script>
<script src="{{ asset('js/theme-settings.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>

@yield('scripts')
@stack('child-scripts')

</body>

</html>
