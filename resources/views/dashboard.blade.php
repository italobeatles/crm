@extends('layouts.app')

@php($pageTitle = 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                <div class="inner"><h3>{{ $metrics['leads'] }}</h3><p>Leads</p></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner"><h3>{{ $metrics['customers'] }}</h3><p>Clientes</p></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner"><h3>{{ $metrics['opportunities'] }}</h3><p>Oportunidades abertas</p></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner"><h3>{{ $metrics['lateActivities'] }}</h3><p>Atividades atrasadas</p></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Funil resumido</h3></div>
                <div class="card-body table-responsive">
                    <table class="table table-striped">
                        <thead><tr><th>Etapa</th><th>Qtd.</th><th>Valor total</th></tr></thead>
                        <tbody>
                        @forelse($pipeline as $item)
                            <tr>
                                <td>{{ \App\Models\Opportunity::stageOptions()[$item->etapa] ?? $item->etapa }}</td>
                                <td>{{ $item->total }}</td>
                                <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Sem dados no funil.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Próximas atividades</h3></div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($pendingActivities as $activity)
                            <li class="list-group-item d-flex justify-content-between">
                                <div>
                                    <strong>{{ $activity->titulo }}</strong>
                                    <div class="text-muted small">{{ ucfirst($activity->tipo) }}</div>
                                </div>
                                <span class="badge text-bg-secondary">{{ optional($activity->data_vencimento)->format('d/m H:i') }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Nenhuma atividade pendente.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-1">Valor em aberto</h4>
                    <p class="display-6 mb-0">R$ {{ number_format($metrics['opportunityValue'], 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
