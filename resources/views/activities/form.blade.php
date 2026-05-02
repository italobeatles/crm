@csrf
@if(isset($activity)) @method('PUT') @endif
<div class="row">
    <div class="col-md-3 mb-3"><label class="form-label">Tipo</label><select name="tipo" class="form-select">@foreach($types as $key => $label)<option value="{{ $key }}" @selected(old('tipo', $activity->tipo ?? 'tarefa') === $key)>{{ $label }}</option>@endforeach</select></div>
    <div class="col-md-3 mb-3"><label class="form-label">Relacionamento</label><select name="relacionado_tipo" class="form-select"><option value="">Sem vínculo</option>@foreach($relatedTypes as $key => $label)<option value="{{ $key }}" @selected(old('relacionado_tipo', $activity->relacionado_tipo ?? '') === $key)>{{ $label }}</option>@endforeach</select></div>
    <div class="col-md-2 mb-3"><label class="form-label">ID relacionado</label><input type="number" name="relacionado_id" class="form-control" value="{{ old('relacionado_id', $activity->relacionado_id ?? '') }}"></div>
    <div class="col-md-4 mb-3"><label class="form-label">Responsável</label><select name="id_usuario_responsavel" class="form-select">@foreach($users as $user)<option value="{{ $user->id }}" @selected((int) old('id_usuario_responsavel', $activity->id_usuario_responsavel ?? auth()->id()) === $user->id)>{{ $user->name }}</option>@endforeach</select></div>
    <div class="col-md-8 mb-3"><label class="form-label">Título</label><input type="text" name="titulo" class="form-control" value="{{ old('titulo', $activity->titulo ?? '') }}" required></div>
    <div class="col-md-2 mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="pendente" @selected(old('status', $activity->status ?? 'pendente') === 'pendente')>Pendente</option><option value="concluida" @selected(old('status', $activity->status ?? '') === 'concluida')>Concluída</option><option value="cancelada" @selected(old('status', $activity->status ?? '') === 'cancelada')>Cancelada</option></select></div>
    <div class="col-md-2 mb-3"><label class="form-label">Vencimento</label><input type="datetime-local" name="data_vencimento" class="form-control" value="{{ old('data_vencimento', isset($activity) && $activity->data_vencimento ? $activity->data_vencimento->format('Y-m-d\\TH:i') : '') }}"></div>
    <div class="col-12 mb-3"><label class="form-label">Descrição</label><textarea name="descricao" class="form-control" rows="4">{{ old('descricao', $activity->descricao ?? '') }}</textarea></div>
</div>
<button class="btn btn-primary">Salvar</button> <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">Cancelar</a>
