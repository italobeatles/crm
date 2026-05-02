@extends('layouts.app')
@php($pageTitle = 'Meu Perfil')
@section('content')
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
        @csrf
        @method('PATCH')
        <div class="col-md-6"><label class="form-label">Nome</label><input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"></div>
        <div class="col-md-6"><label class="form-label">E-mail</label><input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"></div>
        <div class="col-12"><button class="btn btn-primary">Salvar perfil</button></div>
    </form>
</div></div>
@endsection
