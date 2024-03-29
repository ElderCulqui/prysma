<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Compras-Ventas con Laravel y Vue Js- webtraining-it.com">
    <meta name="keyword" content="Sistema Compras-Ventas con Laravel y Vue Js">
    <title>PRYSMA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js">
    <link href="{{ URL::asset('css/plantilla.css') }}" rel="stylesheet">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
<div id="app">
<header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <!--PONER LOGO-->
        <!--<a class="navbar-brand" href="#"></a>-->
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="nav navbar-nav d-md-down-none">
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Dashboard</a>
            </li>
           
        </ul>
        <ul class="nav navbar-nav ml-auto">

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ URL::asset('img/avatars/6.jpg') }}" class="img-avatar" alt="admin@bootstrapmaster.com">
                    <span class="d-md-down-none">{{Auth::user()->usuario}} </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Cuenta</strong>
                    </div>
                    <a class="dropdown-item" href="{{route('logout')}}" 
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-lock"></i> Cerrar sesión</a>

                    <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
                      @csrf
                    </form>
                </div>
            </li>
        </ul>
    </header>

    <div class="app-body">
        <!-- Sidebar -->
            @if (Auth::check())
                @if (Auth::user()->idrol == 1)
                    @include('plantilla.sidebarAdministrador')
                @elseif (Auth::user()->idrol == 2)
                    @include('plantilla.sidebarVendedor')
                @elseif (Auth::user()->idrol == 3)
                    @include('plantilla.sidebarComprador')
                @else
                @endif
            @endif
        <!-- Fin Sidebar -->

        <!-- Contenido Principal -->
            @yield('contenido')
        <!-- /Fin del contenido principal -->
    </div>   
</div>

    <footer class="app-footer">
        <span><a href="http://www.webtraining-it.com/">webtraining-it.com</a> &copy; 2019</span>
        <span class="ml-auto">Desarrollado por <a href="http://www.webtraining-it.com/">webtraining-it.com</a></span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <script src={{ URL::asset('js/app.js') }}></script>
    <script src="{{ URL::asset('js/plantilla.js') }}"></script>
</body>
</html>