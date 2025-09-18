@props([
    'route' => '#',
    'routeParams' => [],
    'text' => '',
    'showCheck' => false,
    'orientation' => 'row' // 'row' ou 'col'
])

@php
    $href = '#';
    if (is_string($route)) {
        if (str_starts_with($route, 'http') || str_starts_with($route, '/')) {
            $href = $route; // already a URL
        } else {
            try {
                $href = route($route, $routeParams);
            } catch (Throwable $e) {
                $href = '#';
            }
        }
    }
@endphp

<a href="{{ $href }}" class="w-96 border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-row items-center justify-center gap-4 mb-4 hover:bg-gray-50 transition-colors duration-200">
    {{ $text }}
    @if($showCheck)
        <x-heroicon-s-check-circle class="w-5 h-5 text-blue-500" />
    @endif
</a>
