@props(['type' => 'text'])

<input type="{{ $type }}" {{ $attributes->merge(['class' => 'block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-gray-500 focus:outline-none focus:ring-1 focus:ring-gray-500']) }}>
