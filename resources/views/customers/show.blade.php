@extends('layouts.app')

@php($pageTitle = 'Detalhes do Cliente')

@section('content')
    @include('partials.action-legend', [
        'actions' => [
            ['icon' => 'fas fa-trash', 'label' => 'Excluir contato', 'class' => 'btn-outline-danger'],
        ],
    ])

    <div class="row">
        <div class="col-lg-7">
            <div class="card"><div class="card-body">
                <h3>{{ $customer->nome }}</h3>
                <p class="mb-1"><strong>Tipo:</strong> {{ \App\Models\Customer::typeOptions()[$customer->tipo] ?? $customer->tipo }}</p>
                <p class="mb-1"><strong>Documento:</strong> {{ $customer->documento ?: '-' }}</p>
                <p class="mb-1"><strong>E-mail:</strong> {{ $customer->email ?: '-' }}</p>
                <p class="mb-1"><strong>Telefone:</strong> {{ $customer->telefone ?: '-' }}</p>
                <p class="mb-0"><strong>Observações:</strong> {{ $customer->observacoes ?: '-' }}</p>
            </div></div>
            <div class="card"><div class="card-header"><h3 class="card-title">Contatos</h3></div><div class="card-body table-responsive">
                <table class="table"><thead><tr><th>Nome</th><th>Cargo</th><th>Contato</th><th></th></tr></thead><tbody>
                @forelse($customer->contacts as $contact)
                    <tr><td>{{ $contact->nome }} @if($contact->principal)<span class="badge text-bg-primary">Principal</span>@endif</td><td>{{ $contact->cargo }}</td><td>{{ $contact->email }}<br>{{ $contact->telefone }}</td><td class="text-end"><form action="{{ route('customers.contacts.destroy', [$customer, $contact]) }}" method="POST" data-confirm-delete>@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Excluir contato" aria-label="Excluir contato"><i class="fas fa-trash"></i></button></form></td></tr>
                @empty
                    <tr><td colspan="4" class="text-muted text-center">Sem contatos cadastrados.</td></tr>
                @endforelse
                </tbody></table>
            </div></div>
        </div>
        <div class="col-lg-5">
            <div class="card"><div class="card-header"><h3 class="card-title">Novo contato</h3></div><div class="card-body">
                <form method="POST" action="{{ route('customers.contacts.store', $customer) }}">
                    @csrf
                    <div class="mb-3"><input type="text" name="nome" class="form-control" placeholder="Nome" required></div>
                    <div class="mb-3"><input type="text" name="cargo" class="form-control" placeholder="Cargo"></div>
                    <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="E-mail"></div>
                    <div class="mb-3"><input type="text" name="telefone" class="form-control" placeholder="Telefone"></div>
                    <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="principal" value="1" id="principal"><label class="form-check-label" for="principal">Contato principal</label></div>
                    <button class="btn btn-primary w-100">Salvar contato</button>
                </form>
            </div></div>
        </div>
    </div>
@endsection
