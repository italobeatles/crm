@extends('layouts.app')
@php($pageTitle = 'Nova Atividade')
@section('content')<div class="card"><div class="card-body"><form method="POST" action="{{ route('activities.store') }}">@include('activities.form')</form></div></div>@endsection
