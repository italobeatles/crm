<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Acesso CRM' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="card card-outline card-primary shadow">
            <div class="card-header text-center">
                <span class="h3"><strong>CRM</strong> Simples</span>
            </div>
            <div class="card-body">
                @include('partials.flash')
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
