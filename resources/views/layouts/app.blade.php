<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <div class="header">
        <a class="brand" href="{{ url('/') }}">
            {{--{{ config('app.name', 'Laravel') }}--}}
            جزوه
        </a>
        <!-- Right Side Of Navbar -->
        <ul class="">
            <!-- Authentication Links -->
            @guest
                <li class="">
                    <a class="" href="{{ route('login') }}">ورود</a>
                </li>
                @if (Route::has('register'))
                    <li class="">
                        <a class="" href="{{ route('register') }}">عضویت</a>
                    </li>
                @endif
            @else
                <li class="nav-item ">
                    <div class=" is-hoverable">
                        <div class="-trigger">
                            <button class="button" aria-haspopup="true" aria-controls="dropdown-menu4">
                                <span>{{ Auth::user()->name }}</span>
                                <span class="icon is-small">
                                <i class="fas fa-angle-down" aria-hidden="true"></i>
                            </span>
                            </button>
                        </div>
                        <div class="dropdown-menu" id="dropdown-menu4" role="menu">
                            <div class="dropdown-content">
                                <div class="dropdown-item">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
    <main class="">
        @yield('content')
    </main>

</div>
</body>
</html>

{{--Default Laravel code for this page--}}
{{--<!DOCTYPE html>--}}
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
{{--<head>--}}
{{--<meta charset="utf-8">--}}
{{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}

{{--<!-- CSRF Token -->--}}
{{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}

{{--<title>{{ config('app.name', 'Laravel') }}</title>--}}

{{--<!-- Scripts -->--}}
{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}

{{--<!-- Styles -->--}}
{{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
{{--</head>--}}
{{--<body>--}}
{{--<div id="app">--}}
{{--<a class="navbar-brand" href="{{ url('/') }}">--}}
{{--{{ config('app.name', 'Laravel') }}--}}
{{--</a>--}}
{{--<!-- Right Side Of Navbar -->--}}
{{--<ul class="navbar-nav ml-auto">--}}
{{--<!-- Authentication Links -->--}}
{{--@guest--}}
{{--<li class="nav-item">--}}
{{--<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>--}}
{{--</li>--}}
{{--@if (Route::has('register'))--}}
{{--<li class="nav-item">--}}
{{--<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>--}}
{{--</li>--}}
{{--@endif--}}
{{--@else--}}
{{--<li class="nav-item dropdown">--}}
{{--<div class="dropdown is-hoverable">--}}
{{--<div class="dropdown-trigger">--}}
{{--<button class="button" aria-haspopup="true" aria-controls="dropdown-menu4">--}}
{{--<span>{{ Auth::user()->name }}</span>--}}
{{--<span class="icon is-small">--}}
{{--<i class="fas fa-angle-down" aria-hidden="true"></i>--}}
{{--</span>--}}
{{--</button>--}}
{{--</div>--}}
{{--<div class="dropdown-menu" id="dropdown-menu4" role="menu">--}}
{{--<div class="dropdown-content">--}}
{{--<div class="dropdown-item">--}}
{{--<a class="dropdown-item" href="{{ route('logout') }}"--}}
{{--onclick="event.preventDefault();--}}
{{--document.getElementById('logout-form').submit();">--}}
{{--{{ __('Logout') }}--}}
{{--</a>--}}

{{--<form id="logout-form" action="{{ route('logout') }}" method="POST"--}}
{{--style="display: none;">--}}
{{--@csrf--}}
{{--</form>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</li>--}}
{{--@endguest--}}
{{--</ul>--}}
{{--<main class="py-4">--}}
{{--@yield('content')--}}
{{--</main>--}}

{{--</div>--}}
{{--</body>--}}
{{--</html>--}}

{{--Default Laravel code for this page--}}