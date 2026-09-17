@extends('layouts.app')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <x-heading>Productos</x-heading>
        @if (auth()->user()->hasRole('Administrador'))
            <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-900 shadow-sm hover:bg-gray-50">
                Nuevo producto
            </a>
        @endif
    </div>

    @if (session('status'))
        <x-alert variant="success" class="mb-4">{{ session('status') }}</x-alert>
    @endif

    <form method="GET" class="mb-6 flex gap-2">
        <x-input name="search" value="{{ request('search') }}" placeholder="Nombre, SKU o categoría" class="max-w-sm" />
        <x-button variant="secondary">Buscar</x-button>
    </form>

    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">SKU</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Producto</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Categoría</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $product->sku }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $product->category }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="font-medium {{ $product->stock <= 5 ? 'text-amber-600' : 'text-gray-900' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if (auth()->user()->hasRole('Administrador'))
                                <div class="flex items-center gap-3">
                                    <x-link href="{{ route('products.edit', $product) }}">Editar</x-link>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}">
                                        @csrf @method('DELETE')
                                        <x-button variant="danger" class="px-2 py-1 text-xs">Eliminar</x-button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-sm text-gray-500">No hay productos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
@endsection
