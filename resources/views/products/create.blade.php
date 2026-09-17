@extends('layouts.app')
@section('content')
    <x-card class="mx-auto max-w-xl">
        <x-heading class="mb-6">Nuevo producto</x-heading>
        <form method="POST" action="{{ route('products.store') }}">
            @include('products._form')
        </form>
    </x-card>
@endsection
