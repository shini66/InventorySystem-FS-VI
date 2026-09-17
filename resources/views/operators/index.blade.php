@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <x-heading>Operarios</x-heading>
        <a href="{{ route('operators.create') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-900 shadow-sm hover:bg-gray-50">
            Nuevo operario
        </a>
    </div>

    @if (session('status'))
        <x-alert variant="success" class="mb-4">{{ session('status') }}</x-alert>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Nombre</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Correo electrónico</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($operators as $operator)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $operator->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $operator->email }}</td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center gap-3">
                                <x-link href="{{ route('operators.edit', $operator) }}">Editar</x-link>
                                <form method="POST" action="{{ route('operators.destroy', $operator) }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" class="px-2 py-1 text-xs">Eliminar</x-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-sm text-gray-500">No hay operarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $operators->links() }}</div>
@endsection
