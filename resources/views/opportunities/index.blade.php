@extends('layouts.app')

@php($pageTitle = 'Oportunidades')

@section('content')
    @include('partials.action-legend', [
        'actions' => [
            ['icon' => 'fas fa-eye', 'label' => 'Visualizar', 'class' => 'btn-outline-secondary'],
            ['icon' => 'fas fa-pen', 'label' => 'Editar', 'class' => 'btn-outline-primary'],
            ['icon' => 'fas fa-trash', 'label' => 'Excluir', 'class' => 'btn-outline-danger'],
            ['icon' => 'fas fa-plus', 'label' => 'Novo registro', 'class' => 'btn-primary'],
        ],
    ])

    <div class="card mb-3"><div class="card-body"><form class="row g-2"><div class="col-md-3"><select name="etapa" class="form-select"><option value="">Etapa</option>@foreach($stages as $key => $label)<option value="{{ $key }}" @selected(request('etapa') === $key)>{{ $label }}</option>@endforeach</select></div><div class="col-md-3"><select name="status" class="form-select"><option value="">Status</option>@foreach($statuses as $key => $label)<option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>@endforeach</select></div><div class="col-md-4"><select name="id_usuario_responsavel" class="form-select"><option value="">Responsável</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected((string) request('id_usuario_responsavel') === (string) $user->id)>{{ $user->name }}</option>@endforeach</select></div><div class="col-md-2 d-flex gap-2"><button class="btn btn-outline-primary flex-fill">Filtrar</button><a href="{{ route('opportunities.create') }}" class="btn btn-primary" title="Nova oportunidade" aria-label="Nova oportunidade"><i class="fas fa-plus"></i></a></div></form></div></div>
    <div class="card"><div class="card-body table-responsive"><table class="table table-hover"><thead><tr><th>Título</th><th>Cliente</th><th>Etapa</th><th>Valor</th><th>Responsável</th><th class="text-end">Ações</th></tr></thead><tbody>@forelse($opportunities as $opportunity)<tr><td>{{ $opportunity->titulo }}</td><td>{{ $opportunity->customer?->nome }}</td><td>{{ $stages[$opportunity->etapa] ?? $opportunity->etapa }}</td><td>R$ {{ number_format($opportunity->valor, 2, ',', '.') }}</td><td>{{ $opportunity->responsavel?->name }}</td><td class="text-end"><div class="action-buttons justify-content-end"><a href="{{ route('opportunities.show', $opportunity) }}" class="btn btn-sm btn-outline-secondary" title="Visualizar" aria-label="Visualizar"><i class="fas fa-eye"></i></a> <a href="{{ route('opportunities.edit', $opportunity) }}" class="btn btn-sm btn-outline-primary" title="Editar" aria-label="Editar"><i class="fas fa-pen"></i></a> <form action="{{ route('opportunities.destroy', $opportunity) }}" method="POST" class="d-inline" data-confirm-delete>@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Excluir" aria-label="Excluir"><i class="fas fa-trash"></i></button></form></div></td></tr>@empty<tr><td colspan="6" class="text-center text-muted">Nenhuma oportunidade encontrada.</td></tr>@endforelse</tbody></table>{{ $opportunities->links() }}</div></div>
@endsection
