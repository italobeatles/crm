@extends('layouts.app')
@php($pageTitle = 'Editar Parâmetro')
@section('content')<div class="card"><div class="card-body"><form method="POST" action="{{ route('admin.settings.update', $setting) }}">@include('admin.settings.form')</form></div></div>@endsection
