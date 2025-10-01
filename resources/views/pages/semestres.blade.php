<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            {{ __('Semestres') }}
        </h2>
    </x-slot>

    <div class="min-h-screen flex flex-col items-center p-4 space-y-4 bg-gray-50">
        <div class="content w-full flex justify-center items-center">
            <div class="flex flex-col">
                @foreach ($semestres as $semestre )
                    <a href="{{ route('semestre', ['semestre' => $semestre]) }}"
                       class="bg-white shadow-md w-96 rounded-full p-4 text-gray-700 flex flex-row items-center justify-center gap-4 mb-4 hover:bg-gray-50 transition-colors duration-200 group"
                       onclick="showLoader(this)">
                        <span>Semestre {{ $semestre->numero }}</span>
                        <div class="hidden group-hover:block">
                            <x-loader size="sm" color="primary" />
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function showLoader(element) {
            // Ajouter un petit délai pour montrer le loader
            element.style.opacity = '0.7';
            element.style.pointerEvents = 'none';
        }
    </script>
</x-app-layout>
