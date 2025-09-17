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
        <div class="w-full max-w-5xl p-4 text-gray-700 flex flex-col gap-3 m-5 my-8 mx-auto ">
            {{-- @isset($edtActif)
                <div class="text-sm text-gray-500">
                    {{ $edtActif->semestre->promotion->nom ?? '' }} — {{ $edtActif->semestre->slug ?? '' }} ({{ ucfirst($edtActif->categorie) }})
                </div>
            @endisset --}}

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

                <div
                    x-data="carousel()"
                    x-init="init()"
                    class="relative"
                >
                    <div
                        x-ref="scroller"
                        class="flex gap-6 overflow-x-auto snap-x snap-mandatory px-4 py-2 items-stretch"
                        style="scrollbar-width: thin; scroll-behavior: smooth;"
                        @scroll.debounce.50="onScroll"
                    >
                        @php $index = 0; @endphp
                        @foreach($grouped as $jour => $items)
                            <button
                                type="button"
                                class="snap-center shrink-0 w-[78vw] sm:w-[420px] md:w-[520px]"
                                :class="activeIndex === {{ $index }} ? 'scale-100' : 'scale-95 opacity-90'"
                                @click="centerTo({{ $index }})"
                                x-ref="card-{{ $index }}"
                            >
                                <div
                                    class="h-full rounded-2xl border border-gray-200 bg-white shadow-sm transition-all duration-300 ease-out"
                                    :class="activeIndex === {{ $index }} ? 'ring-2 ring-indigo-500 shadow-xl' : ''"
                                >
                                    <div class="px-5 pt-5 pb-4">
                                        <div class="mb-3 text-center font-semibold uppercase tracking-wide text-gray-700">{{ $jour }}</div>
                                        <div class="grid grid-cols-1 gap-3">
                                            @foreach($items as $c)
                                                <div class="border rounded-lg p-3 bg-gray-50">
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
                                    </div>
                                </div>
                            </button>
                            @php $index++; @endphp
                        @endforeach
                        <div class="shrink-0 w-4"></div>
                    </div>

                    <div class="pointer-events-none absolute inset-y-0 left-0 w-10 bg-gradient-to-r from-white/80 to-transparent"></div>
                    <div class="pointer-events-none absolute inset-y-0 right-0 w-10 bg-gradient-to-l from-white/80 to-transparent"></div>

                    <script>
                        document.addEventListener('alpine:init', () => {
                            Alpine.data('carousel', () => ({
                                activeIndex: 0,
                                cards: [],
                                scroller: null,
                                init() {
                                    this.scroller = this.$refs.scroller;
                                    // Collect cards by refs pattern card-idx
                                    const cardRefs = Object.keys(this.$refs)
                                        .filter(k => k.startsWith('card-'))
                                        .sort((a,b) => Number(a.split('-')[1]) - Number(b.split('-')[1]));
                                    this.cards = cardRefs.map(k => this.$refs[k]);
                                    this.onScroll();
                                },
                                centerTo(i) {
                                    const el = this.cards[i];
                                    if (!el) return;
                                    el.scrollIntoView({behavior: 'smooth', inline: 'center', block: 'nearest'});
                                    this.activeIndex = i;
                                },
                                onScroll() {
                                    if (!this.scroller) return;
                                    const containerRect = this.scroller.getBoundingClientRect();
                                    const centerX = containerRect.left + containerRect.width / 2;
                                    let closest = 0;
                                    let minDist = Number.POSITIVE_INFINITY;
                                    this.cards.forEach((el, idx) => {
                                        const r = el.getBoundingClientRect();
                                        const cardCenter = r.left + r.width / 2;
                                        const dist = Math.abs(centerX - cardCenter);
                                        if (dist < minDist) { minDist = dist; closest = idx; }
                                    });
                                    this.activeIndex = closest;
                                }
                            }))
                        })
                    </script>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
