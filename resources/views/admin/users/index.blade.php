@extends('layouts.app')

@php($pageTitle = 'Usuários')

@section('content')
    @include('partials.action-legend', [
        'actions' => [
            ['icon' => 'fas fa-eye', 'label' => 'Visualizar', 'class' => 'btn-outline-secondary'],
            ['icon' => 'fas fa-pen', 'label' => 'Editar', 'class' => 'btn-outline-primary'],
            ['icon' => 'fas fa-trash', 'label' => 'Excluir', 'class' => 'btn-outline-danger'],
            ['icon' => 'fas fa-plus', 'label' => 'Novo registro', 'class' => 'btn-primary'],
        ],
    ])

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary" title="Novo usuário" aria-label="Novo usuário">
                <i class="fas fa-plus"></i>
            </a>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Nome</th><th>E-mail</th><th>Perfil</th><th>Status</th><th class="text-end">Ações</th></tr></thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $roles[$user->role] ?? $user->role }}</td>
                        <td>{{ $user->status ? 'Ativo' : 'Inativo' }}</td>
                        <td class="text-end">
                            <div class="action-buttons justify-content-end">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-secondary" title="Visualizar" aria-label="Visualizar"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Editar" aria-label="Editar"><i class="fas fa-pen"></i></a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" data-confirm-delete>@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Excluir" aria-label="Excluir"><i class="fas fa-trash"></i></button></form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
@endsection
