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
        <div class="w-full max-w-5xl border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-col gap-3 m-5 my-8 mx-auto ">
            @isset($edtActif)
                <div class="text-sm text-gray-500">
                    {{ $edtActif->semestre->promotion->nom ?? '' }} — {{ $edtActif->semestre->slug ?? '' }} ({{ ucfirst($edtActif->categorie) }})
                </div>
            @endisset

            @if(!(isset($cours) && $cours->isNotEmpty()))
                <div class="py-2 w-full text-center text-gray-500">Aucun cours à afficher.</div>
            @else
                @php
                    $order = ["lundi","mardi","mercredi","jeudi","vendredi","samedi","dimanche"];
                    $grouped = $cours->groupBy('jour')->sortBy(function($_, $k) use ($order){
                        $idx = array_search(strtolower($k), $order, true);
                        return $idx === false ? 999 : $idx;
                    });
                @endphp

                <div class="relative">
                    <div class="flex gap-4 overflow-x-auto snap-x snap-mandatory pb-2" style="scrollbar-width: thin;">
                        @foreach($grouped as $jour => $items)
                            <section class="min-w-full snap-center shrink-0">
                                <div class="mb-3 text-center font-semibold uppercase">{{ $jour }}</div>
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach($items as $c)
                                        <div class="border rounded p-3">
                                            <div class="flex items-center justify-between">
                                                <div class="font-semibold">{{ $c->debut->format('H:i') }} - {{ $c->fin->format('H:i') }}</div>
                                                <div class="text-xs text-gray-500">Salle: {{ $c->salle->numero ?? 'N/A' }}</div>
                                            </div>
                                            <div class="text-sm text-gray-700">
                                                {{ $c->matiere->unite->nom ?? 'Matière' }} — {{ $c->matiere->enseignant->user->name ?? 'Enseignant' }}
                                            </div>
                                            <div class="text-xs text-gray-500">Type: {{ $c->type ?? 'cours' }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>

                    <div class="pointer-events-none absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-white/80 to-transparent hidden md:block"></div>
                    <div class="pointer-events-none absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-white/80 to-transparent hidden md:block"></div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
