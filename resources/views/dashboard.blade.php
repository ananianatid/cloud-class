<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Acceuil') }}
        </h2>
    </x-slot>

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

    <a href="{{ route('semestre', ['semestre'=>$closestSemestre]) }}"  class="w-96 border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-row items-center justify-center gap-4 mb-4 hover:bg-gray-50 transition-colors duration-200">
        Semestre {{$closestSemestre->numero}}
    </a>
    <div class="w-96 border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-col items-center justify-center gap-4 mb-4">
        @if(isset($closestSemestre))
            <div class="text-sm text-gray-500 mb-4">
                {{ $closestSemestre->date_debut->format('d/m/Y') }} - {{ $closestSemestre->date_fin->format('d/m/Y') }}
            </div>
        @endif

        @if($cours->isEmpty())
            <div class="py-2 w-full text-center text-gray-500">
                Aucune matière disponible pour ce semestre.
            </div>
        @else
            @foreach ($cours as $matiere)
                <a href="{{route("matiere",['semestre'=>$closestSemestre,'matiere'=>$matiere])}}" class="w-full p-3 border rounded hover:bg-gray-50">
                    <div class="font-semibold">
                        {{ $matiere->unite->nom ?? 'Unité inconnue' }}
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $matiere->enseignant->user->name ?? 'Enseignant inconnu' }}
                    </div>
                </a>
            @endforeach
        @endif
    </div>

</x-app-layout>
