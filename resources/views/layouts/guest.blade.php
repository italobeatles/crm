<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Acesso CRM' }}</title>
    <link rel="shortcut icon" href="{{ asset('spica/images/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('spica/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('spica/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('spica/css/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-bg {
            background: url('{{ asset("spica/images/auth/login-bg.jpg") }}') center center / cover no-repeat;
            min-height: 100vh;
        }
        .auth-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            border-radius: 8px;
            box-shadow: 0 4px 30px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth auth-img-bg auth-bg">
                <div class="row w-100">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5 auth-card">
                            <div class="brand-logo text-center mb-4">
                                <span style="font-size:2rem;font-weight:700;color:#223e9c;">CRM Simples</span>
                            </div>
                            @include('partials.flash')
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>