@extends('layouts.app')

@php($pageTitle = 'Detalhes do Lead')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h3>{{ $lead->nome }}</h3>
                    <p class="mb-1"><strong>E-mail:</strong> {{ $lead->email ?: '-' }}</p>
                    <p class="mb-1"><strong>Telefone:</strong> {{ $lead->telefone ?: '-' }}</p>
                    <p class="mb-1"><strong>Status:</strong> {{ \App\Models\Lead::statusOptions()[$lead->status] ?? $lead->status }}</p>
                    <p class="mb-1"><strong>Responsável:</strong> {{ $lead->responsavel?->name }}</p>
                    <p class="mb-0"><strong>Observações:</strong> {{ $lead->observacoes ?: '-' }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('leads.convert', $lead) }}">
                        @csrf
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" checked name="criar_oportunidade" value="1" id="criar_oportunidade">
                            <label class="form-check-label" for="criar_oportunidade">Criar oportunidade inicial</label>
                        </div>
                        <button class="btn btn-success w-100" @disabled($lead->status === 'convertido')>Converter lead</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
