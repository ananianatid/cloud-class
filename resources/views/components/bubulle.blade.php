@props([
    'route' => '#',
    'text' => '',
    'showCheck' => false,
    'orientation' => 'row' // 'row' ou 'col'
])

<a href="{{ route($route) }}" class="w-96 border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-row items-center justify-center gap-4 mb-4 hover:bg-gray-50 transition-colors duration-200">
    {{ $text }}
    @if($showCheck)
        <x-heroicon-s-check-circle class="w-5 h-5 text-blue-500" />
    @endif
</a>
