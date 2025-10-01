@props(['size' => 'md', 'color' => 'primary', 'text' => null])

@php
    $sizeClasses = [
        'xs' => 'loading-xs',
        'sm' => 'loading-sm',
        'md' => 'loading-md',
        'lg' => 'loading-lg',
        'xl' => 'loading-xl'
    ];

    $colorClasses = [
        'primary' => 'text-blue-600',
        'secondary' => 'text-gray-600',
        'success' => 'text-green-600',
        'warning' => 'text-yellow-600',
        'error' => 'text-red-600',
        'white' => 'text-white'
    ];
@endphp

<div class="flex flex-col items-center justify-center space-y-2 {{ $attributes->get('class') }}">
    <span class="loading loading-dots {{ $sizeClasses[$size] }} {{ $colorClasses[$color] }}"></span>
    @if($text)
        <p class="text-sm text-gray-600 animate-pulse">{{ $text }}</p>
    @endif
</div>
