<x-card>
    <x-heading class="mb-6">Iniciar sesión</x-heading>

    <form method="POST" action="{{ route('login.store') }}" wire:submit="login" class="space-y-4">
        @csrf

        <div>
            <x-label>Correo electrónico</x-label>
            <x-input type="email" name="email" wire:model="email" value="{{ old('email') }}" required autofocus />
            <x-error name="email" />
        </div>

        <div>
            <x-label>Contraseña</x-label>
            <x-input type="password" name="password" wire:model="password" required />
            <x-error name="password" />
        </div>

        <x-button class="w-full">Ingresar</x-button>
    </form>
</x-card>
