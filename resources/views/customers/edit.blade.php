@extends('layouts.app')
@php($pageTitle = 'Editar Cliente')
@section('content')<div class="card"><div class="card-body"><form method="POST" action="{{ route('customers.update', $customer) }}">@include('customers.form')</form></div></div>@endsection
