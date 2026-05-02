@extends('layouts.app')
@php($pageTitle = 'Detalhes do Usuário')
@section('content')<div class="card"><div class="card-body"><h3>{{ $user->name }}</h3><p class="mb-1"><strong>E-mail:</strong> {{ $user->email }}</p><p class="mb-1"><strong>Perfil:</strong> {{ \App\Models\User::roleOptions()[$user->role] ?? $user->role }}</p><p class="mb-0"><strong>Status:</strong> {{ $user->status ? 'Ativo' : 'Inativo' }}</p></div></div>@endsection
