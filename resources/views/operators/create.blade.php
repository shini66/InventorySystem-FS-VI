@extends('layouts.app')

@section('content')
    <x-card class="mx-auto max-w-xl">
        <x-heading class="mb-6">Nuevo operario</x-heading>
        <form method="POST" action="{{ route('operators.store') }}">
            @include('operators._form')
        </form>
    </x-card>
@endsection
