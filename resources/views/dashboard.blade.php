<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Acceuil') }}
        </h2>
    </x-slot>

    <div class="w-screen h-screen flex flex-col items-center p-4 space-y-4 ">
        <div class="content w-full h-full flex justify-center items-center">
            <div class="flex flex-col">
                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @isset($closestSemestre)
                <a href="{{ route('semestre', ['semestre'=>$closestSemestre]) }}" class="bg-white shadow-md hover:shadow-xl w-96 rounded-full p-4 text-gray-700 flex flex-row items-center justify-center gap-4 mb-4 hover:bg-gray-50 transition-colors duration-200">
                    Semestre {{$closestSemestre->numero}}
                </a>
                @endisset

                <a href="{{ route('emploi-du-temps-actif') }}" class="bg-white shadow-md w-96 text-blue-700 rounded-full p-4 flex flex-row items-center justify-center gap-4 mb-4 hover:bg-blue-50 transition-colors duration-200">
                    Voir l'emploi du temps actif
                </a>

                <div class="bg-white shadow-md w-96 rounded-2xl p-4 text-gray-700 flex flex-col items-center justify-center gap-4 mb-4 [&>*]:bg-white">
                    @if(isset($closestSemestre))
                        <div class="text-sm text-gray-500 mb-4">
                            {{ $closestSemestre->date_debut->format('d/m/Y') }} - {{ $closestSemestre->date_fin->format('d/m/Y') }}
                        </div>
                    @endif

                    @if(isset($cours) && $cours->isEmpty())
                        <div class="py-2 w-full text-center text-gray-500">
                            Aucune matière disponible pour ce semestre.
                        </div>
                    @elseif(isset($cours))
                        @foreach ($cours as $matiere)
                            <a href="{{route("matiere",['matiere'=>$matiere])}}" class="box w-full p-3 rounded-full hover:bg-gray-50 px-6">
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
            </div>
        </div>
    </div>
</x-app-layout>
