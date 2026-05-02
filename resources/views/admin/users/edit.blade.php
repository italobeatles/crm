@extends('layouts.app')
@php($pageTitle = 'Editar Usuário')
@section('content')<div class="card"><div class="card-body"><form method="POST" action="{{ route('admin.users.update', $user) }}">@include('admin.users.form')</form></div></div>@endsection
