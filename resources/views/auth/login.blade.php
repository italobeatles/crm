<x-guest-layout>
    <p class="login-box-msg">Entre para acessar o painel comercial</p>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="E-mail" value="{{ old('email') }}" required autofocus>
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
        </div>
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Senha" required>
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
        </div>
        <div class="row">
            <div class="col-7">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Lembrar acesso</label>
                </div>
            </div>
            <div class="col-5">
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </div>
        </div>
    </form>
</x-guest-layout>
