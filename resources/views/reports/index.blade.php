@extends('layouts.app')
@php($pageTitle = 'Relatórios')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card"><div class="card-header d-flex justify-content-between"><h3 class="card-title">Oportunidades</h3><a href="{{ route('reports.export', 'opportunities') }}" class="btn btn-sm btn-success">Exportar CSV</a></div><div class="card-body">
            <form class="row g-2 mb-3">
                <div class="col-md-6"><select name="responsavel_opportunity" class="form-select"><option value="">Responsável</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected((string) request('responsavel_opportunity') === (string) $user->id)>{{ $user->name }}</option>@endforeach</select></div>
                <div class="col-md-6"><select name="etapa" class="form-select"><option value="">Etapa</option>@foreach($opportunityStages as $key => $label)<option value="{{ $key }}" @selected(request('etapa') === $key)>{{ $label }}</option>@endforeach</select></div>
                <div class="col-md-6"><input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}"></div>
                <div class="col-md-6"><input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}"></div>
                <div class="col-12"><button class="btn btn-outline-primary">Aplicar</button></div>
            </form>
            <p><strong>Quantidade:</strong> {{ $opportunities->count() }}</p>
            <p><strong>Valor total:</strong> R$ {{ number_format($opportunities->sum('valor'), 2, ',', '.') }}</p>
        </div></div>
    </div>
    <div class="col-lg-6">
        <div class="card"><div class="card-header d-flex justify-content-between"><h3 class="card-title">Atividades</h3><a href="{{ route('reports.export', 'activities') }}" class="btn btn-sm btn-success">Exportar CSV</a></div><div class="card-body">
            <form class="row g-2 mb-3">
                <div class="col-md-6"><select name="responsavel_activity" class="form-select"><option value="">Responsável</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected((string) request('responsavel_activity') === (string) $user->id)>{{ $user->name }}</option>@endforeach</select></div>
                <div class="col-md-6"><select name="tipo_atividade" class="form-select"><option value="">Tipo</option>@foreach($activityTypes as $key => $label)<option value="{{ $key }}" @selected(request('tipo_atividade') === $key)>{{ $label }}</option>@endforeach</select></div>
                <div class="col-md-12"><select name="status_atividade" class="form-select"><option value="">Status</option><option value="pendente" @selected(request('status_atividade') === 'pendente')>Pendente</option><option value="concluida" @selected(request('status_atividade') === 'concluida')>Concluída</option><option value="cancelada" @selected(request('status_atividade') === 'cancelada')>Cancelada</option></select></div>
                <div class="col-12"><button class="btn btn-outline-primary">Aplicar</button></div>
            </form>
            <p><strong>Total:</strong> {{ $activities->count() }}</p>
            <p><strong>Concluídas:</strong> {{ $activities->where('status', 'concluida')->count() }}</p>
            <p><strong>Vencidas:</strong> {{ $activities->where('status', 'pendente')->filter(fn($item) => $item->data_vencimento && $item->data_vencimento->isPast())->count() }}</p>
        </div></div>
    </div>
</div>
@endsection
