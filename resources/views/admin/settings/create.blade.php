@extends('layouts.app')
@php($pageTitle = 'Novo Parâmetro')
@section('content')<div class="card"><div class="card-body"><form method="POST" action="{{ route('admin.settings.store') }}">@include('admin.settings.form')</form></div></div>@endsection
