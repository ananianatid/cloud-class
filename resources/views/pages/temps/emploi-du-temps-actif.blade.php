<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Acceuil') }}
        </h2>
    </x-slot>

    <div class="h-full flex flex-col justify-center items-center">
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif
        <div class="w-4/5 border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-col gap-3 max-w-3xl ">
            @isset($edtActif)
                <div class="text-sm text-gray-500">
                    {{ $edtActif->semestre->promotion->nom ?? '' }} — {{ $edtActif->semestre->slug ?? '' }} ({{ ucfirst($edtActif->categorie) }})
                </div>
            @endisset

            @if(isset($cours) && $cours->isNotEmpty())
                @foreach($cours as $c)
                    <div class="border rounded p-3">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold">{{ ucfirst($c->jour) }} — {{ $c->debut->format('H:i') }} - {{ $c->fin->format('H:i') }}</div>
                            <div class="text-xs text-gray-500">Salle: {{ $c->salle->numero ?? 'N/A' }}</div>
                        </div>
                        <div class="text-sm text-gray-700">
                            {{ $c->matiere->unite->nom ?? 'Matière' }} — {{ $c->matiere->enseignant->user->name ?? 'Enseignant' }}
                        </div>
                        <div class="text-xs text-gray-500">Type: {{ $c->type ?? 'cours' }}</div>
                    </div>
                @endforeach
            @else
                <div class="py-2 w-full text-center text-gray-500">Aucun cours à afficher.</div>
            @endif
        </div>
    </div>
</x-app-layout>
