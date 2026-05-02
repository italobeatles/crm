@csrf
@if(isset($opportunity)) @method('PUT') @endif
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Título</label><input type="text" name="titulo" class="form-control" value="{{ old('titulo', $opportunity->titulo ?? '') }}" required></div>
    <div class="col-md-3 mb-3"><label class="form-label">Cliente</label><select name="id_cliente" class="form-select">@foreach($customers as $customer)<option value="{{ $customer->id }}" @selected((int) old('id_cliente', $opportunity->id_cliente ?? 0) === $customer->id)>{{ $customer->nome }}</option>@endforeach</select></div>
    <div class="col-md-3 mb-3"><label class="form-label">Lead origem</label><select name="id_lead" class="form-select"><option value="">Sem lead</option>@foreach($leads as $lead)<option value="{{ $lead->id }}" @selected((int) old('id_lead', $opportunity->id_lead ?? 0) === $lead->id)>{{ $lead->nome }}</option>@endforeach</select></div>
    <div class="col-md-2 mb-3"><label class="form-label">Valor</label><input type="number" step="0.01" name="valor" class="form-control" value="{{ old('valor', $opportunity->valor ?? 0) }}"></div>
    <div class="col-md-2 mb-3"><label class="form-label">Probabilidade</label><input type="number" name="probabilidade" class="form-control" value="{{ old('probabilidade', $opportunity->probabilidade ?? 10) }}"></div>
    <div class="col-md-3 mb-3"><label class="form-label">Etapa</label><select name="etapa" class="form-select">@foreach($stages as $key => $label)<option value="{{ $key }}" @selected(old('etapa', $opportunity->etapa ?? 'prospeccao') === $key)>{{ $label }}</option>@endforeach</select></div>
    <div class="col-md-2 mb-3"><label class="form-label">Status</label><select name="status" class="form-select">@foreach($statuses as $key => $label)<option value="{{ $key }}" @selected(old('status', $opportunity->status ?? 'aberta') === $key)>{{ $label }}</option>@endforeach</select></div>
    <div class="col-md-3 mb-3"><label class="form-label">Prev. fechamento</label><input type="date" name="data_prevista_fechamento" class="form-control" value="{{ old('data_prevista_fechamento', isset($opportunity) && $opportunity->data_prevista_fechamento ? $opportunity->data_prevista_fechamento->format('Y-m-d') : '') }}"></div>
    <div class="col-md-12 mb-3"><label class="form-label">Responsável</label><select name="id_usuario_responsavel" class="form-select">@foreach($users as $user)<option value="{{ $user->id }}" @selected((int) old('id_usuario_responsavel', $opportunity->id_usuario_responsavel ?? auth()->id()) === $user->id)>{{ $user->name }}</option>@endforeach</select></div>
    <div class="col-12 mb-3"><label class="form-label">Observações</label><textarea name="observacoes" class="form-control" rows="4">{{ old('observacoes', $opportunity->observacoes ?? '') }}</textarea></div>
</div>
<button class="btn btn-primary">Salvar</button> <a href="{{ route('opportunities.index') }}" class="btn btn-outline-secondary">Cancelar</a>
