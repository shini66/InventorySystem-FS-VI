@extends('layouts.app')
@section('content')
    <h1>Editar producto</h1>
    <form method="POST" action="{{ route('products.update', $product) }}">
        @include('products._form')
    </form>
@endsection
