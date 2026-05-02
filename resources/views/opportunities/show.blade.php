@extends('layouts.app')
@php($pageTitle = 'Detalhes da Oportunidade')
@section('content')
<div class="card"><div class="card-body"><h3>{{ $opportunity->titulo }}</h3><p class="mb-1"><strong>Cliente:</strong> {{ $opportunity->customer?->nome }}</p><p class="mb-1"><strong>Etapa:</strong> {{ \App\Models\Opportunity::stageOptions()[$opportunity->etapa] ?? $opportunity->etapa }}</p><p class="mb-1"><strong>Status:</strong> {{ \App\Models\Opportunity::statusOptions()[$opportunity->status] ?? $opportunity->status }}</p><p class="mb-1"><strong>Valor:</strong> R$ {{ number_format($opportunity->valor, 2, ',', '.') }}</p><p class="mb-1"><strong>Responsável:</strong> {{ $opportunity->responsavel?->name }}</p><p class="mb-0"><strong>Observações:</strong> {{ $opportunity->observacoes ?: '-' }}</p></div></div>
@endsection
