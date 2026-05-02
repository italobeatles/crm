@csrf
@if(isset($lead))
    @method('PUT')
@endif
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control" value="{{ old('nome', $lead->nome ?? '') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $lead->email ?? '') }}">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Telefone</label>
        <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $lead->telefone ?? '') }}">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Origem</label>
        <select name="origem" class="form-select">@foreach($origins as $key => $label)<option value="{{ $key }}" @selected(old('origem', $lead->origem ?? 'outro') === $key)>{{ $label }}</option>@endforeach</select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">@foreach($statuses as $key => $label)<option value="{{ $key }}" @selected(old('status', $lead->status ?? 'novo') === $key)>{{ $label }}</option>@endforeach</select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Responsável</label>
        <select name="id_usuario_responsavel" class="form-select">@foreach($users as $user)<option value="{{ $user->id }}" @selected((int) old('id_usuario_responsavel', $lead->id_usuario_responsavel ?? auth()->id()) === $user->id)>{{ $user->name }}</option>@endforeach</select>
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Observações</label>
        <textarea name="observacoes" class="form-control" rows="4">{{ old('observacoes', $lead->observacoes ?? '') }}</textarea>
    </div>
</div>
<button type="submit" class="btn btn-primary">Salvar</button>
<a href="{{ route('leads.index') }}" class="btn btn-outline-secondary">Cancelar</a>
