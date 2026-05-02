@extends('layouts.app')
@php($pageTitle = 'Editar Atividade')
@section('content')<div class="card"><div class="card-body"><form method="POST" action="{{ route('activities.update', $activity) }}">@include('activities.form')</form></div></div>@endsection
