@extends('layouts.app')

@section('content')
    <x-card class="mb-8">
        <x-heading class="mb-6">Registrar movimiento</x-heading>
        <form method="POST" action="{{ route('movements.store') }}">
            @csrf
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="sm:col-span-3">
                    <x-label>Producto</x-label>
                    <x-select name="product_id" required>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} — stock: {{ $product->stock }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                <div>
                    <x-label>Tipo</x-label>
                    <x-select name="type" required>
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                    </x-select>
                </div>

                <div>
                    <x-label>Cantidad</x-label>
                    <x-input type="number" name="quantity" min="1" required />
                    <x-error name="quantity" />
                </div>

                <div>
                    <x-label>Fecha y hora</x-label>
                    <x-input type="datetime-local" name="moved_at" required />
                </div>

                <div>
                    <x-label>Proveedor</x-label>
                    <x-input name="supplier" placeholder="Proveedor para entradas" />
                </div>

                <div>
                    <x-label>Motivo</x-label>
                    <x-input name="reason" placeholder="Motivo para salidas" />
                </div>
            </div>

            <x-button class="mt-6">Registrar movimiento</x-button>
        </form>
    </x-card>

    <h2 class="mb-3 text-lg font-semibold text-gray-900">Historial de movimientos</h2>
    <x-card class="divide-y divide-gray-100 p-0">
        @foreach ($movements as $movement)
            <div class="flex items-center justify-between gap-4 px-4 py-3 text-sm">
                <div class="flex items-center gap-3">
                    <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $movement->type === 'entrada' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($movement->type) }}
                    </span>
                    <span class="text-gray-900">{{ $movement->product->name }}</span>
                </div>
                <div class="text-gray-500">
                    {{ $movement->quantity }} — {{ $movement->moved_at->format('d/m/Y H:i') }}
                </div>
            </div>
        @endforeach
    </x-card>
@endsection
