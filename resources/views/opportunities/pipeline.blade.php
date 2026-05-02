@extends('layouts.app')

@php($pageTitle = 'Pipeline')

@section('content')
    @include('partials.action-legend', [
        'actions' => [
            ['icon' => 'fas fa-arrows-up-down-left-right', 'label' => 'Arrastar card para mudar de etapa', 'class' => 'btn-outline-primary'],
            ['icon' => 'fas fa-eye', 'label' => 'Visualizar oportunidade', 'class' => 'btn-outline-secondary'],
            ['icon' => 'fas fa-pen', 'label' => 'Editar oportunidade', 'class' => 'btn-outline-primary'],
        ],
    ])

    <div class="row" data-pipeline-board>
        @foreach($stages as $key => $label)
            <div class="col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title">{{ $label }}</h3>
                    </div>
                    <div class="card-body kanban-stage bg-light" data-pipeline-stage data-stage="{{ $key }}" data-stage-label="{{ $label }}">
                        <div data-pipeline-list>
                            @forelse($pipeline[$key] as $opportunity)
                                <div
                                    class="card kanban-card mb-2"
                                    draggable="true"
                                    data-pipeline-card
                                    data-stage="{{ $opportunity->etapa }}"
                                    data-update-url="{{ route('opportunities.stage', $opportunity) }}"
                                >
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <strong>{{ $opportunity->titulo }}</strong>
                                                <div class="small text-muted">{{ $opportunity->customer?->nome }}</div>
                                            </div>
                                            <div class="action-buttons">
                                                <a href="{{ route('opportunities.show', $opportunity) }}" class="btn btn-sm btn-outline-secondary" title="Visualizar" aria-label="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('opportunities.edit', $opportunity) }}" class="btn btn-sm btn-outline-primary" title="Editar" aria-label="Editar">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mt-2">R$ {{ number_format($opportunity->valor, 2, ',', '.') }}</div>
                                        <div class="small text-muted">{{ $opportunity->responsavel?->name }}</div>
                                        <span class="badge text-bg-light border mt-2" data-stage-badge>{{ $label }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0" data-empty-state>Sem oportunidades.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
