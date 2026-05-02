@extends('layouts.app')
@php($pageTitle = 'Editar Oportunidade')
@section('content')<div class="card"><div class="card-body"><form method="POST" action="{{ route('opportunities.update', $opportunity) }}">@include('opportunities.form')</form></div></div>@endsection
