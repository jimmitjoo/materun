<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="@yield('body_class')">
<div id="app">
    @if (Route::has('login'))
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    @if (Auth::check())
                        <ul class="nav navbar-nav">
                            <li><a href="{{ url('/') }}">{{ __('workout.create_workout') }}</a></li>
                            <li><a href="{{ url('/list') }}">{{ __('workout.workouts_nearby') }}</a></li>
                        </ul>
                @endif

                <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">{{ __('user.login') }}</a></li>
                            <li><a href="{{ route('register') }}">{{ __('user.register') }}</a></li>
                            <li><a href="{{ route('terms') }}">{{ __('terms') }}</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{ __('user.logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    @endif
    <div class="container">
        @yield('content')
    </div>
</div>

<!-- Scripts -->
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true&libraries=places&key=AIzaSyD3dyF8fM33lfoAiAUZteZNmilb4qEMwPg"></script>
<script src="{{ mix('js/app.js') }}"></script>
<script>
    window.locale = '{{ config('app.locale') }}';
    @if (Auth::check())
        window.user_id = {{ Auth::user()->id }};
        window.user_workout = {{ Auth::user()->my_workout }};
    @endif
</script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-22409709-23', 'auto');
    ga('send', 'pageview');

</script>
</body>
</html>
