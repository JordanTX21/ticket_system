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

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                    aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Offcanvas</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        @if(false)
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            @guest
                                <!-- Authentication Links -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item">
                                    <a class="nav-link dropdown-toggle active" aria-current="page" data-bs-toggle="collapse"
                                        href="#collapseMant" role="button" aria-expanded="false"
                                        aria-controls="collapseMant">
                                        Mantenimiento <span class="caret"></span>
                                    </a>
                                    <div class="collapse" id="collapseMant">
                                        <ul>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Usuario</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="/person">Persona</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="/professor">Docente</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="/student">Estudiante</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link dropdown-toggle active" aria-current="page" data-bs-toggle="collapse"
                                        href="#collapseOptions" role="button" aria-expanded="false"
                                        aria-controls="collapseOptions">
                                        Operaciones <span class="caret"></span>
                                    </a>
                                    <div class="collapse" id="collapseOptions">
                                        <ul>
                                            <li class="nav-item">
                                                <a class="nav-link" href="/menu">Gestionar Tickets</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="/ticket">Generar Ticket Virtual</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="/reception">Despachar Menu</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="/report">Generar Reporte</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="border-top my-3"></li>
                                <li class="nav-item">
                                    <a class="nav-link dropdown-toggle active" aria-current="page" data-bs-toggle="collapse"
                                        href="#collapseUser" role="button" aria-expanded="false"
                                        aria-controls="collapseUser">
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>
                                    <div class="collapse" id="collapseUser">
                                        <ul>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();
                                                                                             document.getElementById('logout-form').submit();">
                                                    Cerrar Sesion
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                        @endif
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="nav-link dropdown-toggle active" aria-current="page" data-bs-toggle="collapse"
                                   href="#collapseOptions" role="button" aria-expanded="false"
                                   aria-controls="collapseOptions">
                                    Operaciones <span class="caret"></span>
                                </a>
                                <div class="collapse" id="collapseOptions">
                                    <ul>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/menu">Gestionar Tickets</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/ticket">Generar Ticket Virtual</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/reception">Despachar Menu</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/report">Generar Reporte</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>
