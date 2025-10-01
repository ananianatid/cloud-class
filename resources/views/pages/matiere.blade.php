<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            {{ __('Matière') }}
        </h2>
    </x-slot>

    <nav class="mb-4 text-sm text-gray-600">
        <a href="{{ route('semestres') }}" class="hover:underline">Semestres</a>
        <span class="mx-1">/</span>
        <a href="{{ route('semestre', ['semestre' => $semestre]) }}" class="hover:underline">Semestre {{ $semestre->numero ?? '' }}</a>
        <span class="mx-1">/</span>
        <span>{{ $matiere->unite->nom ?? 'Matière' }}</span>
    </nav>

    <div
        x-data="{
            total: 4,
            current: 0,
            scrollTo(idx) {
                this.current = Math.max(0, Math.min(this.total - 1, idx));
                this.$refs['card'+this.current].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
        }"
        x-init="
            scrollTo(0);
            window.scrollToCard = (idx) => $data.scrollTo(idx);

            // Écouter les événements du slider
            document.addEventListener('slider-change', (e) => {
                if (e.detail && typeof e.detail.value !== 'undefined') {
                    $data.scrollTo(e.detail.value);
                }
            });
        "
        class="w-full flex flex-col items-center"
    >
        <div class="carousel carousel-center rounded-box w-full md:max-w-md space-x-4 p-4 mx-auto overflow-x-auto">

            {{-- Cours --}}
            <div x-ref="card0" class="carousel-item flex flex-col w-80 mx-4">
                <div class="w-full bg-white shadow-md  rounded-full p-4 text-gray-700 flex justify-center mb-4">
                    Cours
                </div>
                <div class="w-full border bg-white rounded-full p-4 flex flex-col gap-4">
                    @foreach ($fichiers->where('categorie', 'cours') as $fichier)
                        <a href="{{ Storage::url($fichier->chemin) }}" target="_blank" rel="noopener noreferrer"
                           class="py-2 w-full text-center hover:bg-black hover:text-white rounded-full border border-gray-300 transition-colors duration-200">
                            {{ $fichier->nom }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- TD & TP --}}
            <div x-ref="card1" class="carousel-item flex flex-col w-80 mx-4">
                <div class="w-full bg-white shadow-md  rounded-full p-4 text-gray-700 flex justify-center mb-4">
                    TD & TP
                </div>
                <div class="w-full border bg-white rounded-full p-4 flex flex-col gap-4">
                    @foreach ($fichiers->where('categorie', 'td&tp') as $fichier)
                        <a href="{{ Storage::url($fichier->chemin) }}" target="_blank" rel="noopener noreferrer"
                           class="py-2 w-full text-center hover:bg-black hover:text-white rounded-full border border-gray-300 transition-colors duration-200">
                            {{ $fichier->nom }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Évaluations --}}
            <div x-ref="card2" class="carousel-item flex flex-col w-80 mx-4">
                <div class="w-full bg-white shadow-md  rounded-full p-4 text-gray-700 flex justify-center mb-4">
                    Évaluations
                </div>
                <div class="w-full border bg-white rounded-full p-4 flex flex-col gap-4">
                    @foreach ($fichiers->where('categorie', 'evaluation') as $fichier)
                        <a href="{{ Storage::url($fichier->chemin) }}" target="_blank" rel="noopener noreferrer"
                           class="py-2 w-full text-center hover:bg-black hover:text-white rounded-full border border-gray-300 transition-colors duration-200">
                            {{ $fichier->nom }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Autres --}}
            <div x-ref="card3" class="carousel-item flex flex-col w-80 mx-4">
                <div class="w-full bg-white shadow-md  rounded-full p-4 text-gray-700 flex justify-center mb-4">
                    Autres
                </div>
                <div class="w-full border bg-white rounded-full p-4 flex flex-col gap-4">
                    @foreach ($fichiers->where('categorie', 'autre') as $fichier)
                        <a href="{{ Storage::url($fichier->chemin) }}" target="_blank" rel="noopener noreferrer"
                           class="py-2 w-full text-center hover:bg-black hover:text-white rounded-full border border-gray-300 transition-colors duration-200">
                            {{ $fichier->nom }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="max-w-md mx-auto mt-3 w-full flex justify-center">
            <x-glass-slider
                :min="0"
                :max="3"
                :step="1"
                :value="0"
                width="320px"
            />
        </div>
    </div>
</x-app-layout>
