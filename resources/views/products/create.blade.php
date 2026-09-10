@extends('layouts.app')
@section('content')
    <h1>Nuevo producto</h1>
    <form method="POST" action="{{ route('products.store') }}">
        @include('products._form')
    </form>
@endsection

