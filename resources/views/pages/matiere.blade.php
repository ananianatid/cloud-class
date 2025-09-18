<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            {{ __('Matière') }}
        </h2>
    </x-slot>

    <div
        x-data="{
            total: 4,
            current: 0,
            scrollTo(idx) {
                this.current = Math.max(0, Math.min(this.total - 1, idx));
                this.$refs['card'+this.current].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
        }"
        x-init="scrollTo(0)"
        class="w-full flex flex-col items-center"
    >
        <div class="carousel carousel-center bg-white rounded-box w-full md:max-w-md space-x-4 p-4 mx-auto overflow-x-auto">

            {{-- Cours --}}
            <div x-ref="card0" class="carousel-item flex flex-col w-80 mx-4">
                <div class="w-full border border-gray-300 rounded-2xl p-4 text-gray-700 flex justify-center mb-4">
                    Cours
                </div>
                <div class="w-full border border-gray-300 rounded-2xl p-4 flex flex-col gap-4">
                    @foreach ($fichiers->where('categorie', 'cours') as $fichier)
                        <a href="#"
                           class="py-2 w-full text-center hover:bg-black hover:text-white rounded-full border border-gray-300 transition-colors duration-200">
                            {{ $fichier->nom }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- TD & TP --}}
            <div x-ref="card1" class="carousel-item flex flex-col w-80 mx-4">
                <div class="w-full border border-gray-300 rounded-2xl p-4 text-gray-700 flex justify-center mb-4">
                    TD & TP
                </div>
                <div class="w-full border border-gray-300 rounded-2xl p-4 flex flex-col gap-4">
                    @foreach ($fichiers->where('categorie', 'td&tp') as $fichier)
                        <a href="#"
                           class="py-2 w-full text-center hover:bg-black hover:text-white rounded-full border border-gray-300 transition-colors duration-200">
                            {{ $fichier->nom }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Évaluations --}}
            <div x-ref="card2" class="carousel-item flex flex-col w-80 mx-4">
                <div class="w-full border border-gray-300 rounded-2xl p-4 text-gray-700 flex justify-center mb-4">
                    Évaluations
                </div>
                <div class="w-full border border-gray-300 rounded-2xl p-4 flex flex-col gap-4">
                    @foreach ($fichiers->where('categorie', 'evaluation') as $fichier)
                        <a href="#"
                           class="py-2 w-full text-center hover:bg-black hover:text-white rounded-full border border-gray-300 transition-colors duration-200">
                            {{ $fichier->nom }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Autres --}}
            <div x-ref="card3" class="carousel-item flex flex-col w-80 mx-4">
                <div class="w-full border border-gray-300 rounded-2xl p-4 text-gray-700 flex justify-center mb-4">
                    Autres
                </div>
                <div class="w-full border border-gray-300 rounded-2xl p-4 flex flex-col gap-4">
                    @foreach ($fichiers->where('categorie', 'autre') as $fichier)
                        <a href="#"
                           class="py-2 w-full text-center hover:bg-black hover:text-white rounded-full border border-gray-300 transition-colors duration-200">
                            {{ $fichier->nom }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="max-w-md mx-auto mt-3 w-full flex justify-center">
            <input
                type="range"
                min="0"
                max="3"
                step="1"
                x-model.number="current"
                @input="scrollTo(current)"
                class="range range-primary w-80 mx-auto"
            >
        </div>
    </div>
</x-app-layout>
