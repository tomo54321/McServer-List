<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} @if(isset($pagename))- {{$pagename}}@endif</title>

    <meta name="description" content="{{ isset($pagedesc) ? $pagedesc : __('Discover the best Minecraft servers with our multiplayer server list. Advertise your server for free and get more players!') }}" />
    <meta name="keywords" content="minecraft, minecraft servers, minecraft server list, mc server list" />
    <link rel="canonical" href="{{URL()->current()}}" />
    <link rel="home" href="{{config("app.url")}}" />

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    {!! htmlScriptTagJsApi() !!}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/list.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a href="{{route('home')}}" class="nav-link">Servers</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('auction.current')}}" class="nav-link">Sponsored Server</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a href="{{route('server.create')}}" class="nav-link">Add Server</a>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->username }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a href="{{route('account.servers')}}" class="dropdown-item">My Servers</a>
                                    <a class="dropdown-item" href="{{route('account.settings')}}">Account Settings</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="my-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center text-md-left col-md-3 mb-3 mb-md-0">
                        <span class="h4 font-weight-bold">{{config("app.name")}}</span>
                        <span class="d-block text-muted">&copy; {{date("Y")}} All rights reserved.</span>
                        <small class="text-muted">Minecraft and all associated Minecraft images are copyright of Mojang AB. {{config("app.name")}} is not affiliated with Minecraft or Mojang AB.</small>
                    </div>
                    <div class="col-6 text-center text-md-left col-md-3">
                        <ul>
                            <li class="title">Directory</li>
                            <li><a href="{{route('home')}}">All Servers</a></li>
                            <li><a href="{{route('home')}}?sortby=newest">Newest Servers</a></li>
                            <li><a href="{{route('home')}}?sortby=updated">Recently Updated Servers</a></li>
                        </ul>
                    </div>

                    <div class="col-6 text-center text-md-left col-md-3">
                        <ul>
                            <li class="title">Account</li>
                            @auth
                                <li><a href="{{route('server.create')}}">Add Server</a></li>
                                <li><a href="{{route('account.servers')}}">My Servers</a></li>
                                <li><a href="{{route('account.settings')}}">Account Settings</a></li>
                            @else
                            <li><a href="{{route('login')}}">Login</a></li>
                            <li><a href="{{route('register')}}">Register</a></li>
                            @endauth
                        </ul>
                    </div>

                    <div class="col-12 text-center text-md-left col-md-3 mt-3 mt-md-0">
                        <ul>
                            <li class="title">Legal</li>
                            <li><a href="{{route('legal.terms')}}">Terms and Conditions</a></li>
                            <li><a href="{{route('legal.privacy')}}">Privacy Policy</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </footer>
    </div>

    @yield("inline-script")
</body>
</html>
