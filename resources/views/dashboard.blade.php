@extends('layouts.app')

@php($pageTitle = 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6 grid-margin stretch-card">
            <div class="card" style="background:#6640b2;color:#fff;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Leads</p>
                            <h3 class="font-weight-bold mb-0">{{ $metrics['leads'] }}</h3>
                        </div>
                        <i class="mdi mdi-account-plus icon-lg" style="opacity:0.4;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 grid-margin stretch-card">
            <div class="card" style="background:#00d082;color:#fff;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Clientes</p>
                            <h3 class="font-weight-bold mb-0">{{ $metrics['customers'] }}</h3>
                        </div>
                        <i class="mdi mdi-domain icon-lg" style="opacity:0.4;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 grid-margin stretch-card">
            <div class="card" style="background:#ffbf36;color:#fff;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Oportunidades abertas</p>
                            <h3 class="font-weight-bold mb-0">{{ $metrics['opportunities'] }}</h3>
                        </div>
                        <i class="mdi mdi-funnel icon-lg" style="opacity:0.4;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 grid-margin stretch-card">
            <div class="card" style="background:#f83e37;color:#fff;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Atividades atrasadas</p>
                            <h3 class="font-weight-bold mb-0">{{ $metrics['lateActivities'] }}</h3>
                        </div>
                        <i class="mdi mdi-alert-circle icon-lg" style="opacity:0.4;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Funil resumido</p>
                    <div class="table-responsive">
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
        </div>
        <div class="col-lg-5 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Proximas atividades</p>
                    <ul class="list-group">
                        @forelse($pendingActivities as $activity)
                            <li class="list-group-item d-flex justify-content-between">
                                <div>
                                    <strong>{{ $activity->titulo }}</strong>
                                    <div class="text-muted small">{{ ucfirst($activity->tipo) }}</div>
                                </div>
                                <span class="badge badge-outline-secondary">{{ optional($activity->data_vencimento)->format('d/m H:i') }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Nenhuma atividade pendente.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card" style="background:linear-gradient(135deg,#6640b2,#223e9c);color:#fff;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1">Valor em aberto</p>
                        <h2 class="font-weight-bold mb-0">R$ {{ number_format($metrics['opportunityValue'], 2, ',', '.') }}</h2>
                    </div>
                    <i class="mdi mdi-currency-usd icon-lg" style="opacity:0.4;"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
