@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
    <div class="mx-auto max-w-sm">
        <livewire:login />
        <p class="mt-4 text-center">
            <x-link href="{{ route('register') }}">Registrar una empresa</x-link>
        </p>
    </div>
@endsection
