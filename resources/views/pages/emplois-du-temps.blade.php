<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            {{ __('Emplois du temps') }}
        </h2>
    </x-slot>


    <div class="min-h-screen flex flex-col items-center p-4 space-y-4">
        <div class="content w-full flex justify-center items-center">
            <div class="flex flex-col w-full max-w-2xl">
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
                            <details class="group bg-white shadow-md rounded-3xl overflow-hidden">
                                <summary class="cursor-pointer select-none list-none px-6 py-4 flex items-center justify-between ">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-3xl bg-blue-100 text-blue-700 font-semibold">{{ $semNumero }}</span>
                                        <span class="font-semibold text-gray-800 text-lg">Semestre {{ $semNumero }}</span>
                                    </div>
                                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </summary>
                                <div class="px-6 pb-4">
                                    <div class="space-y-3">
                                        @foreach($list as $edt)
                                            <a href="{{ route('emploi-du-temps', $edt) }}" class="block bg-gray-50 rounded-full p-4 px-6 hover:bg-gray-100">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <div class="font-medium text-gray-800">
                                                            {{ ucfirst($edt->categorie) }}
                                                        </div>
                                                        <div class="text-sm text-gray-600">
                                                            {{ \Carbon\Carbon::parse($edt->debut)->format('d/m/Y') }} -
                                                            {{ \Carbon\Carbon::parse($edt->fin)->format('d/m/Y') }}
                                                        </div>
                                                    </div>
                                                    @if($edt->actif)
                                                        <div class="text-xs text-blue-700 bg-blue-100 px-3 py-1 rounded-full font-medium">Actif</div>
                                                    @endif
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </details>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
