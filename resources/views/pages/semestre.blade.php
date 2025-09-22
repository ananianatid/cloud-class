<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            {{ __('Semestre') }}
        </h2>
    </x-slot>
    <nav class="mb-4 text-sm text-gray-600">
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
    <div class="w-96 border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-col items-center justify-center gap-4 mb-4">
        @if(isset($matieres) && $matieres->isNotEmpty())
            @foreach($matieres as $matiere)
                {{-- <div class="border border-gray-200 rounded-xl p-4">
                    <div class="font-semibold text-gray-800">
                        {{ $matiere->unite->nom ?? 'Unité inconnue' }}
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $matiere->enseignant->user->name ?? 'Enseignant inconnu' }}
                    </div>
                </div> --}}
                <x-bubulle-rfull route="matiere" :route-params="['matiere' => $matiere]" :text="$matiere->unite->nom ?? 'Unité inconnue'" :show-check="false" />
            @endforeach
        @else
            <div class="text-center text-gray-500">Aucune matière disponible pour ce semestre.</div>
        @endif
    </div>
</x-app-layout>
