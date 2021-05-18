<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bulletin Board</title>
    @yield('meta')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css', 'build') }}" rel="stylesheet">
</head>
<body id="app">
<header>
    <nav class="navbar navbar-expand-md navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                Bulletin Board
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    @foreach(array_slice($menuPages, 0, 3) as $page)
                        @if($page->children()->exists())
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle"
                                   href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $page->getMenuTitle() }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item"
                                       href="{{ route('page', page_path($page)) }}">{{ $page->getMenuTitle() }}</a>
                                    @foreach ($page->children as $child)
                                        <a class="dropdown-item"
                                           href="{{ route('page', page_path($child)) }}">{{ $child->getMenuTitle() }}</a>
                                    @endforeach
                                </div>
                            </li>
                        @else
                        <li>
                            <a class="nav-link" href="{{ route('page', page_path($page)) }}">{{ $page->getMenuTitle() }}</a>
                        </li>
                        @endif
                    @endforeach
                    @if($morePages = array_slice($menuPages, 3))
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle"
                               href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Others
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @foreach ($morePages as $page)
                                    <a class="dropdown-item"
                                       href="{{ route('page', page_path($page)) }}">{{ $page->getMenuTitle() }}</a>
                                @endforeach
                            </div>
                        </li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                @can('admin-panel')
                                <a class="dropdown-item" href="{{ route('admin.home') }}">Admin</a>
                                @endcan
                                <a class="dropdown-item" href="{{ route('cabinet.home') }}">Cabinet</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    @section('search')
        @include('layouts.partials.search', ['category' => null, 'route' => route('adverts.index')])
    @show
</header>

<main class="py-4 app-content">
    <div class="container">
        @section('breadcrumbs', Breadcrumbs::render())
        @yield('breadcrumbs')
        @include('layouts.partials.flash')
        @yield('content')
    </div>
</main>

<footer>
    <div class="container text-center">
        <div class="border-top pt-3">
            <p>&copy; {{ date('Y') }} - theifel</p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="{{ mix('js/app.js', 'build') }}" defer></script>
</body>
</html>
