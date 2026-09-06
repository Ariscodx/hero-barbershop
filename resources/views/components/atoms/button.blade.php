@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
])

@php
$baseClasses = 'inline-flex items-center justify-center px-4 py-2 border text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200';

$variants = [
    'primary' => 'border-transparent text-white bg-accent hover:bg-accent-hover focus:ring-accent',
    'secondary' => 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-accent',
    'danger' => 'border-transparent text-white bg-red-600 hover:bg-red-700 focus:ring-red-500',
    'outline' => 'border-accent text-accent bg-transparent hover:bg-accent hover:text-white focus:ring-accent',
];

$classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
