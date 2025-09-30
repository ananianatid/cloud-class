<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            {{ __('Emploi du temps') }}
        </h2>
    </x-slot>

    <div class="w-screen h-screen flex flex-col items-center p-4 space-y-4 bg-gray-50">
        <div class="content w-full h-full flex justify-center items-center">
            <div class="flex flex-col w-full max-w-4xl">
                @if(session('error'))
                    <div class="mb-4 p-3 border border-red-300 text-red-700 rounded">{{ session('error') }}</div>
                @endif

                <div class="mb-6 text-center">
                    <div class="text-sm text-gray-600 mb-2">
                        @isset($edt)
                            {{ \Carbon\Carbon::parse($edt->debut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($edt->fin)->format('d/m/Y') }}
                        @else
                            Aucun emploi du temps actif
                        @endisset
                    </div>
                    @isset($edt)
                        @if($edt->actif)
                            <span class="inline-block text-xs text-blue-700 bg-blue-100 px-3 py-1 rounded-full font-medium">Actif</span>
                        @endif
                    @endisset
                </div>

                @php
                    $grouped = $cours->groupBy('jour');
                    $jours = $joursOrder ?? ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];
                @endphp

                <div x-data="{ current: 0, total: {{ count($jours) }}, scrollTo(idx) { this.current = Math.max(0, Math.min(this.total - 1, idx)); this.$refs['card'+this.current].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' }); } }">
                    <div class="carousel carousel-center bg-white rounded-box w-full space-x-4 p-4 mx-auto overflow-x-auto">
                        @foreach($jours as $idx => $jour)
                            @php $dayCourses = $grouped[$jour] ?? collect(); @endphp
                            <div x-ref="card{{ $idx }}" class="carousel-item flex flex-col w-80 mx-2">
                                <div class="bg-white box rounded-full p-4 text-gray-700 flex flex-row items-center justify-center gap-4 mb-4 hover:bg-gray-50 transition-colors duration-200 capitalize font-semibold">
                                    {{ $jour }}
                                </div>
                                <div class="bg-white box rounded-2xl p-4 text-gray-700 flex flex-col gap-3 mb-4 [&>*]:bg-white">
                                    @forelse($dayCourses as $c)
                                        <div class="box rounded-xl flex flex-col gap-1 bg-blue-50 overflow-hidden p-3 border border-blue-200">
                                            <div class="flex justify-between items-center">
                                                <span class="font-semibold text-blue-700 text-sm md:text-base">{{ $c->debut->format('H:i') }} - {{ $c->fin->format('H:i') }}</span>
                                                <span class="text-gray-500 text-xs">{{ $c->matiere->unite->code ?? '' }}</span>
                                            </div>
                                            <div class="font-medium text-gray-800 text-lg">{{ $c->matiere->unite->nom ?? '' }}</div>
                                            <div class="text-gray-600 text-sm">{{ $c->matiere->enseignant->user->name ?? '' }} — {{ strtoupper($c->type) }} @if($c->salle) • Salle {{ $c->salle->nom }} @endif</div>
                                        </div>
                                    @empty
                                        <div class="text-sm text-gray-500 text-center py-4">Aucun cours</div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="max-w-md mx-auto mt-3 w-full flex justify-center items-center">
                        <input type="range" min="0" max="{{ max(0, count($jours) - 1) }}" step="1" x-model.number="current" @input="scrollTo(current)" class="range range-primary w-80 mx-auto">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
