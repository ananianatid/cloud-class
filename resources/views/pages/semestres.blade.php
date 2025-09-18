<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            {{ __('Semestres') }}
        </h2>
    </x-slot>
    @foreach ($semestres as $semestre )
        {{-- <a href="#" class="w-96 border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-row items-center justify-center gap-4 mb-4 hover:bg-gray-50 transition-colors duration-200">
            Semestre {{ $semestre->numero }}
            @if($showCheck)
                <x-heroicon-s-check-circle class="w-5 h-5 text-blue-500" />
            @endif
        </a> --}}
        <x-bubulle route="semestre" :route-params="['semestre' => $semestre]" text="Semestre {{ $semestre->numero }}" :show-check="false" />
    @endforeach

</x-app-layout>
