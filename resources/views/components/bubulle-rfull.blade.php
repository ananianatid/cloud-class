@props([
    'route' => null,
    'routeParams' => [],
    'text' => '',
    'showCheck' => false
])

@php($href = $route ? route($route, $routeParams) : '#')

<a href="{{ $href }}" class="py-2 w-full text-center hover:bg-black hover:text-white rounded-full border border-gray-300 transition-colors duration-200">
    {{ $text }}
    @if($showCheck)
        <x-heroicon-s-check-circle class="w-5 h-5 text-blue-500 inline ml-2" />
    @endif
</a>

