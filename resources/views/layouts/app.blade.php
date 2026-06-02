<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CRM Simples' }}</title>
    <link rel="shortcut icon" href="{{ asset('spica/images/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('spica/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('spica/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('spica/css/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container-scroller d-flex">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="mdi mdi-view-quilt menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('leads.index') }}">
                        <i class="mdi mdi-account-plus menu-icon"></i>
                        <span class="menu-title">Leads</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('customers.index') }}">
                        <i class="mdi mdi-domain menu-icon"></i>
                        <span class="menu-title">Clientes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('opportunities.index') }}">
                        <i class="mdi mdi-funnel menu-icon"></i>
                        <span class="menu-title">Oportunidades</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pipeline') }}">
                        <i class="mdi mdi-view-column menu-icon"></i>
                        <span class="menu-title">Pipeline</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('activities.index') }}">
                        <i class="mdi mdi-calendar-check menu-icon"></i>
                        <span class="menu-title">Atividades</span>
                    </a>
                </li>
                @if(auth()->user()->canManageTeams())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reports.index') }}">
                            <i class="mdi mdi-file-chart menu-icon"></i>
                            <span class="menu-title">Relatorios</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">
                            <i class="mdi mdi-account-group menu-icon"></i>
                            <span class="menu-title">Usuarios</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.settings.index') }}">
                            <i class="mdi mdi-cog menu-icon"></i>
                            <span class="menu-title">Parametros</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>
                    <div class="navbar-brand-wrapper">
                        <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
                            <span style="color:#fff;font-weight:700;font-size:1.5rem;">CRM</span>
                        </a>
                        <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
                            <span style="color:#fff;font-weight:700;font-size:1.2rem;">C</span>
                        </a>
                    </div>
                    <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1 text-white">
                        {{ $pageTitle ?? 'CRM Simples' }}
                    </h4>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item dropdown nav-profile me-3">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                                <i class="mdi mdi-account-circle" style="font-size:1.5rem;"></i>
                                <span class="nav-profile-name ms-2">{{ auth()->user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="mdi mdi-account text-primary"></i>
                                    Meu perfil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="mdi mdi-logout text-primary"></i>
                                        Sair
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper">
                    @include('partials.flash')
                    @yield('content')
                </div>
                <footer class="footer">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-sm-flex justify-content-center justify-content-sm-between py-2">
                                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                                    CRM Simples &copy; {{ date('Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script src="{{ asset('spica/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('spica/js/off-canvas.js') }}"></script>
    <script src="{{ asset('spica/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('spica/js/template.js') }}"></script>
</body>
</html>