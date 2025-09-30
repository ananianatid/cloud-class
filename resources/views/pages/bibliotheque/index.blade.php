<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bibliothèque') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Barre de recherche -->
            <div class="mb-6 sm:mb-8">
                <form method="GET" action="{{ route('bibliotheque') }}" class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <div class="flex-1">
                        <input type="text"
                               name="search"
                               value="{{ $searchQuery }}"
                               placeholder="Rechercher un livre..."
                               class="w-full px-4 py-3 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base sm:text-sm">
                    </div>
                    <div class="flex gap-2 sm:gap-4">
                        <button type="submit"
                                class="flex-1 sm:flex-none px-6 py-3 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 text-sm sm:text-base">
                            Rechercher
                        </button>
                        @if($searchQuery)
                            <a href="{{ route('bibliotheque') }}"
                               class="flex-1 sm:flex-none px-6 py-3 sm:py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 text-sm sm:text-base">
                                Effacer
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Résultats de recherche -->
            @if($searchQuery)
                <div class="mb-4 sm:mb-6">
                    <p class="text-sm sm:text-base text-gray-600">
                        @if($livres->count() > 0)
                            {{ $livres->count() }} livre(s) trouvé(s) pour "{{ $searchQuery }}"
                        @else
                            Aucun livre trouvé pour "{{ $searchQuery }}"
                        @endif
                    </p>
                </div>
            @endif

            <!-- Liste des livres -->
            @if($livres->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
                    @foreach($livres as $livre)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                            <!-- Image du livre en format 2:3 -->
                            <div class="aspect-[2/3]  bg-gray-200">
                                @if($livre['api_data']['thumbnail'])
                                    <img src="{{ $livre['api_data']['thumbnail'] }}"
                                         alt="{{ $livre['api_data']['title'] }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Contenu du livre -->
                            <div class="p-3 sm:p-4">
                                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-2 line-clamp-2">
                                    {{ $livre['api_data']['title'] }}
                                </h3>

                                <p class="text-xs sm:text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Auteur(s):</span>
                                    {{ implode(', ', $livre['api_data']['authors']) }}
                                </p>

                                <p class="text-xs sm:text-sm text-gray-600 mb-3">
                                    <span class="font-medium">Catégorie:</span>
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                        {{ $livre['categorie'] }}
                                    </span>
                                </p>

                                <!-- Actions -->
                                <div class="flex gap-2 sm:gap-1">
                                    @if($livre['chemin_fichier'])
                                        <a href="{{ Storage::url($livre['chemin_fichier']) }}"
                                           target="_blank"
                                           class="flex-1 bg-green-600 text-white text-xs sm:text-xs px-3 py-2 sm:px-2 sm:py-1 rounded hover:bg-green-700 transition duration-200 text-center">
                                            Télécharger
                                        </a>
                                    @endif

                                    @if(isset($livre['api_data']['info_link']) && $livre['api_data']['info_link'])
                                        <a href="{{ $livre['api_data']['info_link'] }}"
                                           target="_blank"
                                           class="flex-1 bg-blue-600 text-white text-xs sm:text-xs px-3 py-2 sm:px-2 sm:py-1 rounded hover:bg-blue-700 transition duration-200 text-center">
                                            Google Books
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Message quand aucun livre -->
                <div class="text-center py-8 sm:py-12">
                    <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h3 class="mt-2 text-sm sm:text-base font-medium text-gray-900">Aucun livre trouvé</h3>
                    <p class="mt-1 text-xs sm:text-sm text-gray-500 px-4">
                        @if($searchQuery)
                            Essayez de modifier vos critères de recherche.
                        @else
                            Aucun livre n'est disponible dans la bibliothèque pour le moment.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>
