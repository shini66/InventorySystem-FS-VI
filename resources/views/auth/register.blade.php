@extends('layouts.app')

@section('title', 'Registrar empresa')

@section('content')
    <x-card class="mx-auto max-w-lg">
        <x-heading class="mb-6">Registrar empresa</x-heading>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <h2 class="mb-3 text-lg font-medium text-gray-900">Empresa</h2>
            <div class="space-y-4">
                <div>
                    <x-label>Nombre</x-label>
                    <x-input name="company_name" value="{{ old('company_name') }}" required />
                    <x-error name="company_name" />
                </div>

                <div>
                    <x-label>CUIT</x-label>
                    <x-input name="cuit" value="{{ old('cuit') }}" required />
                    <x-error name="cuit" />
                </div>

                <div>
                    <x-label>Dirección</x-label>
                    <x-input name="address" value="{{ old('address') }}" required />
                    <x-error name="address" />
                </div>

                <div>
                    <x-label>Teléfono</x-label>
                    <x-input name="phone" value="{{ old('phone') }}" required />
                    <x-error name="phone" />
                </div>
            </div>

            <h2 class="mt-6 mb-3 text-lg font-medium text-gray-900">Administrador</h2>
            <div class="space-y-4">
                <div>
                    <x-label>Nombre</x-label>
                    <x-input name="name" value="{{ old('name') }}" required />
                    <x-error name="name" />
                </div>

                <div>
                    <x-label>Correo electrónico</x-label>
                    <x-input type="email" name="email" value="{{ old('email') }}" required />
                    <x-error name="email" />
                </div>

                <div>
                    <x-label>Contraseña</x-label>
                    <x-input type="password" name="password" required />
                    <x-error name="password" />
                </div>

                <div>
                    <x-label>Confirmar contraseña</x-label>
                    <x-input type="password" name="password_confirmation" required />
                </div>
            </div>

            <x-button class="mt-6 w-full">Crear empresa</x-button>
        </form>

        <p class="mt-4 text-center">
            <x-link href="{{ route('login') }}">Ya tengo una cuenta</x-link>
        </p>
    </x-card>
@endsection
