<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            {{ __('Semestres') }}
        </h2>
    </x-slot>

    <div class="w-screen h-screen flex flex-col items-center p-4 space-y-4 bg-gray-50">
        <div class="content w-full h-full flex justify-center items-center">
            <div class="flex flex-col">
                @foreach ($semestres as $semestre )
                    <a href="{{ route('semestre', ['semestre' => $semestre]) }}" class="bg-white box w-96 rounded-full p-4 text-gray-700 flex flex-row items-center justify-center gap-4 mb-4 hover:bg-gray-50 transition-colors duration-200">
                        Semestre {{ $semestre->numero }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
