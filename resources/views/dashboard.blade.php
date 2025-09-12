<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                -- <x-welcome /> --
            </div>
        </div>
    </div> --}}

    <div class="h-full flex flex-col justify-center items-center">
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif
        <div class="w-4/5 border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-col items-center justify-center gap-4  ">
            @if($semestres->isEmpty())
                <div class="py-2 w-full text-center text-gray-500">
                    Aucun semestre disponible.
                </div>
            @else
                @foreach ($semestres as $semestre)
                    <a href="" class="py-2 w-full text-center hover:bg-black hover:text-white hover:rounded-full">
                        Semestre {{ $semestre->nombre }}
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
