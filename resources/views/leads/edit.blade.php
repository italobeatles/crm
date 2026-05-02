@extends('layouts.app')

@php($pageTitle = 'Editar Lead')

@section('content')
    <div class="card"><div class="card-body"><form method="POST" action="{{ route('leads.update', $lead) }}">@include('leads.form')</form></div></div>
@endsection
