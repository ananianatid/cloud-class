<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bibliothèque') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(isset($error))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ $error }}
                        </div>
                    @endif

                    <h1 class="text-2xl font-bold mb-6">Bibliothèque</h1>
                    
                    @if(isset($livres) && $livres->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($livres as $livre)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $livre->titre }}</h3>
                                        
                                        @if($livre->auteur)
                                            <p class="text-sm text-gray-600 mb-2">
                                                <strong>Auteur:</strong> {{ $livre->auteur->nom }}
                                            </p>
                                        @endif
                                        
                                        @if($livre->categorie)
                                            <p class="text-sm text-gray-600 mb-2">
                                                <strong>Catégorie:</strong> {{ $livre->categorie->nom }}
                                            </p>
                                        @endif
                                        
                                        @if($livre->isbn)
                                            <p class="text-sm text-gray-600 mb-2">
                                                <strong>ISBN:</strong> {{ $livre->isbn }}
                                            </p>
                                        @endif
                                        
                                        @if($livre->description)
                                            <p class="text-sm text-gray-700 mt-3">{{ Str::limit($livre->description, 100) }}</p>
                                        @endif
                                        
                                        @if($livre->disponible)
                                            <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full mt-2">
                                                Disponible
                                            </span>
                                        @else
                                            <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full mt-2">
                                                Non disponible
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 text-lg mb-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun livre trouvé</h3>
                            <p class="text-gray-500">La bibliothèque est actuellement vide.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>