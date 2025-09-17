<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Semestres') }}
        </h2>
    </x-slot>

    <div class="h-full flex flex-col justify-center items-center">
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif
        <div class="w-4/5 border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-col items-center justify-center gap-4 max-w-96 ">
            @php
                $grouped = isset($emploisDuTemps) ? $emploisDuTemps->groupBy(fn($e) => $e->semestre->slug ?? 'inconnu') : collect();
            @endphp
            @if($grouped->isEmpty())
                <div class="py-2 w-full text-center text-gray-500">
                    Aucun emploi du temps disponible.
                </div>
            @else
                @foreach ($grouped as $slug => $items)
                    <details class="w-full border rounded-lg">
                        <summary class="cursor-pointer select-none px-4 py-3 font-semibold flex items-center justify-between">
                            <span>{{ strtoupper($slug) }}</span>
                            <span class="text-sm text-gray-500">{{ $items->count() }} élément(s)</span>
                        </summary>
                        <div class="p-4 flex flex-col gap-2">
                            @foreach ($items as $edt)
                                <div class="w-full p-3 border rounded">
                                    <div class="text-sm text-gray-500">
                                        {{ $edt->semestre->promotion->nom ?? '' }} — {{ $edt->semestre->slug ?? '' }}
                                    </div>
                                    <div class="font-semibold">
                                        {{ $edt->nom }} ({{ $edt->categorie }}) {{ $edt->actif ? '— Actif' : '' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $edt->debut }} — {{ $edt->fin }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </details>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
