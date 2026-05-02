@csrf
@if(isset($user)) @method('PUT') @endif
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Nome</label><input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">E-mail</label><input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required></div>
    <div class="col-md-4 mb-3"><label class="form-label">Telefone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}"></div>
    <div class="col-md-4 mb-3"><label class="form-label">Perfil</label><select name="role" class="form-select">@foreach($roles as $key => $label)<option value="{{ $key }}" @selected(old('role', $user->role ?? 'sales') === $key)>{{ $label }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label class="form-label">Ativo</label><select name="status" class="form-select"><option value="1" @selected((string) old('status', $user->status ?? 1) === '1')>Sim</option><option value="0" @selected((string) old('status', $user->status ?? 1) === '0')>Não</option></select></div>
    <div class="col-md-6 mb-3"><label class="form-label">Senha</label><input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}></div>
    <div class="col-md-6 mb-3"><label class="form-label">Confirmar senha</label><input type="password" name="password_confirmation" class="form-control" {{ isset($user) ? '' : 'required' }}></div>
</div>
<button class="btn btn-primary">Salvar</button> <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancelar</a>
