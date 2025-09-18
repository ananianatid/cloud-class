<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            {{ __('matiere') }}
        </h2>
    </x-slot>
    <div x-data="{ total: 4, current: 0, scrollTo(idx) { this.current = Math.max(0, Math.min(this.total - 1, idx)); this.$refs['card'+this.current].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' }); } }" class="w-full flex flex-col items-center overflow-x-hidden">
        <div class="carousel carousel-center bg-white rounded-box w-full md:max-w-md space-x-4 p-4 mx-auto">
            <div x-ref="card0" class="carousel-item flex flex-col w-80 mx-4">
                <div class="w-full border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-row items-center justify-center gap-4 mb-4 hover:bg-gray-50 transition-colors duration-200">
                    Cours
                </div>
                <div class="w-full border border-gray-300 rounded-2xl p-4 text-gray-700 flex flex-col items-center justify-center gap-4 mb-4">
                    <x-bubulle-rfull route="matiere" text="Algorithmique et programmation" :show-check="false" />
                    <x-bubulle-rfull route="matiere" text="Analyse et conception de systemes" :show-check="false" />
                    <x-bubulle-rfull route="matiere" text="Architecture des ordinateurs" :show-check="false" />
                    <x-bubulle-rfull route="matiere" text="Base de donnees" :show-check="false" />
                    <x-bubulle-rfull route="matiere" text="Electronique" :show-check="false" />
                    <x-bubulle-rfull route="matiere" text="Electronique" :show-check="false" />
                    <x-bubulle-rfull route="matiere" text="Electronique" :show-check="false" />
                </div>
            </div>
        </div>
        <div class="max-w-md mx-auto mt-3 w-full flex justify-center items-center overflow-x-hidden">
            <input type="range" min="0" max="3" step="1" x-model.number="current" @input="scrollTo(current)" class="range range-primary w-80 mx-auto">
        </div>
    </div>

</x-app-layout>
