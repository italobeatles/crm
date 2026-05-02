@extends('layouts.app')
@php($pageTitle = 'Novo Cliente')
@section('content')<div class="card"><div class="card-body"><form method="POST" action="{{ route('customers.store') }}">@include('customers.form')</form></div></div>@endsection
