<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CRM Simples' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                            {{ auth()->user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">Meu perfil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Sair</button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="{{ route('dashboard') }}" class="brand-link text-decoration-none">
                    <span class="brand-text fw-light">CRM Simples</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                        <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link"><i class="nav-icon fas fa-chart-line"></i><p>Dashboard</p></a></li>
                        <li class="nav-item"><a href="{{ route('leads.index') }}" class="nav-link"><i class="nav-icon fas fa-user-plus"></i><p>Leads</p></a></li>
                        <li class="nav-item"><a href="{{ route('customers.index') }}" class="nav-link"><i class="nav-icon fas fa-building"></i><p>Clientes</p></a></li>
                        <li class="nav-item"><a href="{{ route('opportunities.index') }}" class="nav-link"><i class="nav-icon fas fa-funnel-dollar"></i><p>Oportunidades</p></a></li>
                        <li class="nav-item"><a href="{{ route('pipeline') }}" class="nav-link"><i class="nav-icon fas fa-columns"></i><p>Pipeline</p></a></li>
                        <li class="nav-item"><a href="{{ route('activities.index') }}" class="nav-link"><i class="nav-icon fas fa-calendar-check"></i><p>Atividades</p></a></li>
                        @if(auth()->user()->canManageTeams())
                            <li class="nav-item"><a href="{{ route('reports.index') }}" class="nav-link"><i class="nav-icon fas fa-file-csv"></i><p>Relatórios</p></a></li>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link"><i class="nav-icon fas fa-users-cog"></i><p>Usuários</p></a></li>
                            <li class="nav-item"><a href="{{ route('admin.settings.index') }}" class="nav-link"><i class="nav-icon fas fa-sliders-h"></i><p>Parâmetros</p></a></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>

        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid py-3">
                    <h1 class="mb-0">{{ $pageTitle ?? 'CRM Simples' }}</h1>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid pb-4">
                    @include('partials.flash')
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>
</html>
