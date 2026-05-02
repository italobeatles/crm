@extends('layouts.app')

@php($pageTitle = 'Clientes')

@section('content')
    @include('partials.action-legend', [
        'actions' => [
            ['icon' => 'fas fa-eye', 'label' => 'Visualizar', 'class' => 'btn-outline-secondary'],
            ['icon' => 'fas fa-pen', 'label' => 'Editar', 'class' => 'btn-outline-primary'],
            ['icon' => 'fas fa-trash', 'label' => 'Excluir', 'class' => 'btn-outline-danger'],
            ['icon' => 'fas fa-plus', 'label' => 'Novo registro', 'class' => 'btn-primary'],
        ],
    ])

    <div class="card mb-3"><div class="card-body">
        <form class="row g-2">
            <div class="col-md-3"><input type="text" name="nome" class="form-control" placeholder="Nome" value="{{ request('nome') }}"></div>
            <div class="col-md-2"><select name="tipo" class="form-select"><option value="">Tipo</option>@foreach($types as $key => $label)<option value="{{ $key }}" @selected(request('tipo') === $key)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-md-2"><select name="status" class="form-select"><option value="">Status</option>@foreach($statuses as $key => $label)<option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-md-3"><select name="id_usuario_responsavel" class="form-select"><option value="">Responsável</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected((string) request('id_usuario_responsavel') === (string) $user->id)>{{ $user->name }}</option>@endforeach</select></div>
            <div class="col-md-2 d-flex gap-2"><button class="btn btn-outline-primary flex-fill">Filtrar</button><a href="{{ route('customers.create') }}" class="btn btn-primary" title="Novo cliente" aria-label="Novo cliente"><i class="fas fa-plus"></i></a></div>
        </form>
    </div></div>
    <div class="card"><div class="card-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Nome</th><th>Tipo</th><th>Status</th><th>Responsável</th><th class="text-end">Ações</th></tr></thead>
            <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>{{ $customer->nome }}</td>
                    <td>{{ $types[$customer->tipo] ?? $customer->tipo }}</td>
                    <td>{{ $statuses[$customer->status] ?? $customer->status }}</td>
                    <td>{{ $customer->responsavel?->name }}</td>
                    <td class="text-end">
                        <div class="action-buttons justify-content-end">
                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-secondary" title="Visualizar" aria-label="Visualizar"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary" title="Editar" aria-label="Editar"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline" data-confirm-delete>@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Excluir" aria-label="Excluir"><i class="fas fa-trash"></i></button></form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">Nenhum cliente encontrado.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $customers->links() }}
    </div></div>
@endsection
