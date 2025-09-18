<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            {{ __('Emplois du temps') }}
        </h2>
    </x-slot>
    <div class="w-full max-w-3xl mx-auto px-4 py-6">
        @if(session('error'))
            <div class="mb-4 p-3 border border-red-300 text-red-700 rounded">{{ session('error') }}</div>
        @endif

        @if($emploisDuTemps->isEmpty())
            <div class="text-center text-gray-500">Aucun emploi du temps disponible.</div>
        @else
            @php
                // Grouper par numéro de semestre et trier du plus grand au plus petit
                $groupedBySemestre = $emploisDuTemps
                    ->groupBy(fn($e) => (int)($e->semestre->numero ?? -999))
                    ->sortKeysDesc();
            @endphp

            <div class="space-y-4">
                @foreach($groupedBySemestre as $semNumero => $list)
                    <details class="group border border-gray-200 rounded-xl">
                        <summary class="cursor-pointer select-none list-none px-4 py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full border border-gray-300 text-sm text-gray-700">{{ $semNumero }}</span>
                                <span class="font-semibold text-gray-800">Semestre {{ $semNumero }}</span>
                                {{-- <span class="text-sm text-gray-500">— {{ $list->first()->semestre->promotion->nom ?? '' }}</span> --}}
                            </div>
                            <svg class="h-5 w-5 text-gray-500 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div class="px-4 pb-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($list as $edt)
                                    <div class="border border-gray-200 rounded-lg p-3">
                                        <div class="font-medium text-gray-800">
                                            {{ ucfirst($edt->categorie) }}
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            Créé le {{ optional($edt->created_at)->format('d/m/Y') }}
                                        </div>
                                        @if($edt->actif)
                                            <div class="mt-1 inline-block text-[10px] text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full">Actif</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
