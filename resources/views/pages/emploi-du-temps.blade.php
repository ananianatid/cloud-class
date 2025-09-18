<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            {{ __('Emploi du temps') }}
        </h2>
    </x-slot>

    <div class="w-full max-w-4xl mx-auto px-4 py-6">
        @if(session('error'))
            <div class="mb-4 p-3 border border-red-300 text-red-700 rounded">{{ session('error') }}</div>
        @endif

        <div class="mb-4 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($edt->debut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($edt->fin)->format('d/m/Y') }}
            </div>
            @if($edt->actif)
                <span class="inline-block text-[10px] text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full">Actif</span>
            @endif
        </div>

        @php
            $grouped = $cours->groupBy('jour');
            $jours = $joursOrder ?? ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];
        @endphp

        <div class="carousel carousel-center bg-white rounded-box w-full space-x-4 p-4 mx-auto overflow-x-auto">
            @foreach($jours as $idx => $jour)
                @php $dayCourses = $grouped[$jour] ?? collect(); @endphp
                <div class="carousel-item flex flex-col w-80 mx-2">
                    <div class="w-full border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-row items-center justify-center gap-4 mb-4 hover:bg-gray-50 transition-colors duration-200 capitalize">
                        {{ $jour }}
                    </div>
                    <div class="w-full border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-col gap-3 mb-4">
                        @forelse($dayCourses as $c)
                            <div class="w-full border border-blue-200 rounded-xl flex flex-col gap-1 bg-blue-50 overflow-hidden p-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-blue-700 text-sm md:text-base">{{ $c->debut->format('H:i') }} - {{ $c->fin->format('H:i') }}</span>
                                    <span class="text-gray-500 text-xs">{{ $c->matiere->unite->code ?? '' }}</span>
                                </div>
                                <div class="font-medium text-gray-800 text-lg">{{ $c->matiere->unite->nom ?? '' }}</div>
                                <div class="text-gray-600 text-sm">{{ $c->matiere->enseignant->user->name ?? '' }} — {{ strtoupper($c->type) }} @if($c->salle) • Salle {{ $c->salle->nom }} @endif</div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">Aucun cours</div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
