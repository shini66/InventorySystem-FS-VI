<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Depósito iDT')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">
    @auth
        <nav class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-4 px-4 py-3 sm:px-6">
                <div class="flex flex-wrap items-center gap-1">
                    @if (auth()->user()->hasRole('Administrador'))
                        <a href="{{ route('dashboard') }}"
                           class="rounded-md px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('products.index') }}"
                           class="rounded-md px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('products.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                            Productos
                        </a>
                        <a href="{{ route('operators.index') }}"
                           class="rounded-md px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('operators.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                            Operarios
                        </a>
                    @endif
                    <a href="{{ route('movements.index') }}"
                       class="rounded-md px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('movements.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                        Movimientos
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-600">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-md px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    @endauth
    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6">@yield('content')</main>
    @livewireScripts
</body>
</html>
