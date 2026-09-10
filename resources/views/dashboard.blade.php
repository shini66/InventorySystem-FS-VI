@extends('layouts.app')
@section('content')
    <div class="cards">
        <article><strong>{{ $productCount }}</strong><span>Productos</span></article>
        <article><strong>{{ $totalStock }}</strong><span>Unidades</span></article>
        <article><strong>{{ $lowStockCount }}</strong><span>Stock bajo</span></article>
    </div>

    <h2>Stock por categoría</h2>
    @foreach ($stockByCategory as $row)
        <p>{{ $row->category }}: {{ $row->total_stock }} unidades</p>
    @endforeach

    @foreach ($recentMovements as $movement)
        <p>
            {{ $movement->moved_at->format('d/m/Y H:i') }} —
            {{ ucfirst($movement->type) }} de {{ $movement->quantity }} —
            {{ $movement->product->name }}
        </p>
    @endforeach

@endsection
