@extends('layouts.app')
@php($pageTitle = 'Novo Usuário')
@section('content')<div class="card"><div class="card-body"><form method="POST" action="{{ route('admin.users.store') }}">@include('admin.users.form')</form></div></div>@endsection
