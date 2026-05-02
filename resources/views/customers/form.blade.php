@csrf
@if(isset($customer))
    @method('PUT')
@endif
<div class="row">
    <div class="col-md-2 mb-3"><label class="form-label">Tipo</label><select name="tipo" class="form-select">@foreach($types as $key => $label)<option value="{{ $key }}" @selected(old('tipo', $customer->tipo ?? 'pj') === $key)>{{ $label }}</option>@endforeach</select></div>
    <div class="col-md-5 mb-3"><label class="form-label">Nome</label><input type="text" name="nome" class="form-control" value="{{ old('nome', $customer->nome ?? '') }}" required></div>
    <div class="col-md-2 mb-3"><label class="form-label">Documento</label><input type="text" name="documento" class="form-control" value="{{ old('documento', $customer->documento ?? '') }}"></div>
    <div class="col-md-3 mb-3"><label class="form-label">Status</label><select name="status" class="form-select">@foreach($statuses as $key => $label)<option value="{{ $key }}" @selected(old('status', $customer->status ?? 'ativo') === $key)>{{ $label }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label class="form-label">E-mail</label><input type="email" name="email" class="form-control" value="{{ old('email', $customer->email ?? '') }}"></div>
    <div class="col-md-4 mb-3"><label class="form-label">Telefone</label><input type="text" name="telefone" class="form-control" value="{{ old('telefone', $customer->telefone ?? '') }}"></div>
    <div class="col-md-4 mb-3"><label class="form-label">Responsável</label><select name="id_usuario_responsavel" class="form-select">@foreach($users as $user)<option value="{{ $user->id }}" @selected((int) old('id_usuario_responsavel', $customer->id_usuario_responsavel ?? auth()->id()) === $user->id)>{{ $user->name }}</option>@endforeach</select></div>
    <div class="col-12 mb-3"><label class="form-label">Observações</label><textarea name="observacoes" class="form-control" rows="4">{{ old('observacoes', $customer->observacoes ?? '') }}</textarea></div>
</div>
<button type="submit" class="btn btn-primary">Salvar</button>
<a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancelar</a>
