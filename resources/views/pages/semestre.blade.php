<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            {{ __('Semestre') }}
        </h2>
    </x-slot>

    <div class="min-h-screen flex flex-col items-center p-4 space-y-4 bg-gray-50">
        <div class="content w-full flex justify-center items-center">
            <div class="flex flex-col">
                <nav class="mb-4 text-sm text-gray-600 text-center">
                    <a href="{{ route('semestres') }}" class="hover:underline">Semestres</a>
                    <span class="mx-1">/</span>
                    <span>Semestre {{ $semestre->numero ?? '' }}</span>
                </nav>

                <div class="mb-6 text-center text-gray-700">
                    <div class="text-lg font-medium">Semestre {{ $semestre->numero ?? '' }}</div>
                    @if(isset($semestre->date_debut, $semestre->date_fin))
                        <div class="text-sm text-gray-500">
                            {{ $semestre->date_debut->format('d/m/Y') }} - {{ $semestre->date_fin->format('d/m/Y') }}
                        </div>
                    @endif
                </div>

                <div class="bg-white shadow-md w-96 rounded-3xl p-4 text-gray-700 flex flex-col items-center justify-center gap-4 mb-4 [&>*]:bg-white">
                    @if(isset($matieres) && $matieres->isNotEmpty())
                        @foreach($matieres as $matiere)
                            <a href="{{ route('matiere', ['matiere' => $matiere]) }}" class=" w-full p-3 rounded-full hover:bg-cyan-500 hover:shadow-md hover:shadow-cyan-500/50 hover:text-white px-6">
                                <div class="font-semibold">
                                    {{ $matiere->unite->nom ?? 'Unité inconnue' }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $matiere->enseignant->user->name ?? 'Enseignant inconnu' }}
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center text-gray-500">Aucune matière disponible pour ce semestre.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
