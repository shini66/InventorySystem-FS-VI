@extends('layouts.app')
@section('content')
    <div class="grid gap-4 sm:grid-cols-3">
        <x-card>
            <p class="text-3xl font-bold text-gray-900">{{ $productCount }}</p>
            <p class="text-sm text-gray-500">Productos</p>
        </x-card>
        <x-card>
            <p class="text-3xl font-bold text-gray-900">{{ $totalStock }}</p>
            <p class="text-sm text-gray-500">Unidades</p>
        </x-card>
        <x-card class="{{ $lowStockCount > 0 ? 'border-amber-300 bg-amber-50' : '' }}">
            <p class="text-3xl font-bold {{ $lowStockCount > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $lowStockCount }}</p>
            <p class="text-sm {{ $lowStockCount > 0 ? 'text-amber-700' : 'text-gray-500' }}">Stock bajo</p>
        </x-card>
    </div>

    <h2 class="mt-8 mb-3 text-lg font-semibold text-gray-900">Stock por categoría</h2>
    <x-card class="divide-y divide-gray-100 p-0">
        @foreach ($stockByCategory as $row)
            <p class="flex justify-between px-4 py-3 text-sm text-gray-900">
                <span>{{ $row->category }}</span>
                <span class="font-medium">{{ $row->total_stock }} unidades</span>
            </p>
        @endforeach
    </x-card>

    <h2 class="mt-8 mb-3 text-lg font-semibold text-gray-900">Movimientos recientes</h2>
    <x-card class="divide-y divide-gray-100 p-0">
        @foreach ($recentMovements as $movement)
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
