@csrf
@if ($operator->exists) @method('PUT') @endif

<div class="space-y-4">
    <div>
        <x-label>Nombre</x-label>
        <x-input name="name" value="{{ old('name', $operator->name) }}" required />
        <x-error name="name" />
    </div>

    <div>
        <x-label>Correo electrónico</x-label>
        <x-input type="email" name="email" value="{{ old('email', $operator->email) }}" required />
        <x-error name="email" />
    </div>

    <div>
        <x-label>Contraseña{{ $operator->exists ? ' (dejar vacía para conservar)' : '' }}</x-label>
        <x-input type="password" name="password" :required="! $operator->exists" />
        <x-error name="password" />
    </div>

    <div>
        <x-label>Confirmar contraseña</x-label>
        <x-input type="password" name="password_confirmation" :required="! $operator->exists" />
    </div>
</div>

<x-button class="mt-6">{{ $operator->exists ? 'Actualizar' : 'Crear operario' }}</x-button>
