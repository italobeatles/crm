@extends('layouts.app')
@php($pageTitle = 'Nova Oportunidade')
@section('content')<div class="card"><div class="card-body"><form method="POST" action="{{ route('opportunities.store') }}">@include('opportunities.form')</form></div></div>@endsection
