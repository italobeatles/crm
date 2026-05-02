@extends('layouts.app')

@php($pageTitle = 'Novo Lead')

@section('content')
    <div class="card"><div class="card-body"><form method="POST" action="{{ route('leads.store') }}">@include('leads.form')</form></div></div>
@endsection
