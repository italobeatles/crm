<x-guest-layout>
    <p class="login-box-msg">Cadastro rápido de usuário operacional</p>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Nome" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="E-mail" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <input type="text" name="phone" class="form-control" placeholder="Telefone" value="{{ old('phone') }}">
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Senha" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar senha" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Criar acesso</button>
    </form>
</x-guest-layout>
