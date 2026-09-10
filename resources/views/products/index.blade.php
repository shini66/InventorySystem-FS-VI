@extends('layouts.app')
@section('content')
<h1>Productos</h1>
@if (session('status')) <p class="success">{{ session('status') }}</p> @endif

<form method="GET">
    <input name="search" value="{{ request('search') }}"
           placeholder="Nombre, SKU o categoría">
    <button>Buscar</button>
</form>

<a href="{{ route('products.create') }}">Nuevo producto</a>
<table>
    <thead><tr><th>SKU</th><th>Producto</th><th>Categoría</th><th>Stock</th><th></th></tr></thead>
    <tbody>
    @forelse ($products as $product)
        <tr>
            <td>{{ $product->sku }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category }}</td>
            <td>{{ $product->stock }}</td>
            <td>
                <a href="{{ route('products.edit', $product) }}">Editar</a>
                <form method="POST" action="{{ route('products.destroy', $product) }}">
                    @csrf @method('DELETE')
                    <button>Eliminar</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="5">No hay productos.</td></tr>
    @endforelse
    </tbody>
</table>
{{ $products->links() }}
@endsection
