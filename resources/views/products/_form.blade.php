@csrf
@if ($product->exists) @method('PUT') @endif

<div class="space-y-4">
    <div>
        <x-label>Nombre</x-label>
        <x-input name="name" value="{{ old('name', $product->name) }}" required />
        <x-error name="name" />
    </div>

    <div>
        <x-label>SKU</x-label>
        <x-input name="sku" value="{{ old('sku', $product->sku) }}" required />
        <x-error name="sku" />
    </div>

    <div>
        <x-label>Categoría</x-label>
        <x-input name="category" value="{{ old('category', $product->category) }}" required />
    </div>

    <div>
        <x-label>Descripción</x-label>
        <x-textarea name="description">{{ old('description', $product->description) }}</x-textarea>
    </div>
</div>

<x-button class="mt-6">{{ $product->exists ? 'Actualizar' : 'Guardar' }}</x-button>
