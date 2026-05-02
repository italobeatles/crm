@extends('layouts.app')

@php($pageTitle = 'Parâmetros')

@section('content')
    @include('partials.action-legend', [
        'actions' => [
            ['icon' => 'fas fa-pen', 'label' => 'Editar', 'class' => 'btn-outline-primary'],
            ['icon' => 'fas fa-trash', 'label' => 'Excluir', 'class' => 'btn-outline-danger'],
            ['icon' => 'fas fa-plus', 'label' => 'Novo registro', 'class' => 'btn-primary'],
        ],
    ])

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.settings.create') }}" class="btn btn-primary" title="Novo parâmetro" aria-label="Novo parâmetro">
                <i class="fas fa-plus"></i>
            </a>
        </div>
        <div class="card-body table-responsive">
            <table class="table">
                <thead><tr><th>Chave</th><th>Descrição</th><th class="text-end">Ações</th></tr></thead>
                <tbody>
                @foreach($settings as $setting)
                    <tr>
                        <td>{{ $setting->chave }}</td>
                        <td>{{ $setting->descricao }}</td>
                        <td class="text-end">
                            <div class="action-buttons justify-content-end">
                                <a href="{{ route('admin.settings.edit', $setting) }}" class="btn btn-sm btn-outline-primary" title="Editar" aria-label="Editar"><i class="fas fa-pen"></i></a>
                                <form action="{{ route('admin.settings.destroy', $setting) }}" method="POST" class="d-inline" data-confirm-delete>@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Excluir" aria-label="Excluir"><i class="fas fa-trash"></i></button></form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $settings->links() }}
        </div>
    </div>
@endsection
