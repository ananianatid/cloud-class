<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bibliothèque') }}
        </h2>
    </x-slot>

    <div class="w-screen h-screen flex flex-col items-center p-4 space-y-4 bg-gray-50">
        <div class="content w-full h-full flex flex-col justify-center items-center">
            @if(isset($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ $error }}
                </div>
            @endif

            <div class="flex">
                <!-- Champ de recherche principal -->
                <div class="bg-white rounded-full py-2 px-2 w-max box flex items-center">
                    <input
                        type="text"
                        name="recherche"
                        placeholder="Rechercher un livre, un auteur..."
                        class="outline-none bg-transparent text-gray-700 px-4 py-2 w-80"
                        aria-label="Recherche"
                    >
                </div>

                <!-- Bouton de recherche dans sa propre bulle -->
                <div class="bg-white rounded-full py-2 px-2 box flex items-center justify-center mx-4">
                    <button type="submit" class="p-2 text-gray-700 hover:text-blue-600 hover:bg-gray-100 rounded-full transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>
                </div>
            </div>

            @if(isset($livres) && $livres->count() > 0)
                <div class="books p-10 flex flex-row gap-4 flex-wrap justify-center">
                    @foreach($livres as $livre)
                        <div class="book flex flex-col m-2 relative" data-google-books-url="{{ $livre->google_books_url }}">
                            @if($livre->image_url)
                                <img class="w-32 h-48 object-cover rounded-2xl shadow-md" src="{{ $livre->image_url }}" alt="{{ $livre->titre }}">
                            @else
                                <div class="w-32 h-48 bg-gray-200 rounded-2xl shadow-md flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                    </svg>
                                </div>
                            @endif

                            <div class="mt-2 hidden">
                                <h3 class="font-semibold text-gray-900 text-sm">{{ $livre->titre }}</h3>
                                <p class="text-gray-600 text-xs">
                                    @if($livre->auteur)
                                        Par {{ $livre->auteur }}
                                    @else
                                        Auteur inconnu
                                    @endif
                                </p>
                            </div>

                            <!-- Bouton des trois points -->
                            <div class="flex justify-start">
                                <button class="bookMenuButton hover:bg-gray-100 hover:rounded-full p-2 transition-all duration-200" data-book-id="{{ $livre->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Dropdown Menu -->
                            <div class="bookDropdown absolute top-full left-1/2 transform -translate-x-1/2 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden">
                                <div class="p-3">
                                    <!-- Titre et auteur -->
                                    <div class="mb-3 pb-3 border-b border-gray-200">
                                        <h4 class="font-semibold text-gray-900 text-sm">{{ $livre->titre }}</h4>
                                        <p class="text-gray-600 text-xs">
                                            @if($livre->auteur)
                                                Par {{ $livre->auteur }}
                                            @else
                                                Auteur inconnu
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Options du menu -->
                                    <div class="space-y-1">
                                        <button class="bookAction flex items-center space-x-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 w-full text-left" data-action="share">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 7.5 7.5m-7.5-7.5H3m5.217 2.186V3m0 0h2.25M12 3v2.25" />
                                            </svg>
                                            <span class="text-sm">Partager</span>
                                        </button>

                                        <button class="bookAction flex items-center space-x-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 w-full text-left" data-action="details">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                            </svg>
                                            <span class="text-sm">Voir sur Google Books</span>
                                        </button>

                                        <button class="bookAction flex items-center space-x-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 w-full text-left" data-action="download">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            <span class="text-sm">Télécharger</span>
                                        </button>
                                    </div>
                                </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des dropdowns des livres
            const bookMenuButtons = document.querySelectorAll('.bookMenuButton');
            const bookDropdowns = document.querySelectorAll('.bookDropdown');

            console.log('Boutons trouvés:', bookMenuButtons.length);
            console.log('Dropdowns trouvés:', bookDropdowns.length);

            bookMenuButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const bookCard = this.closest('.book');
                    const dropdown = bookCard.querySelector('.bookDropdown');

                    console.log('Bouton cliqué, dropdown trouvé:', dropdown);

                    // Fermer tous les autres dropdowns
                    bookDropdowns.forEach(dd => {
                        if (dd !== dropdown) {
                            dd.classList.add('hidden');
                        }
                    });

                    // Toggle le dropdown actuel
                    if (dropdown) {
                        dropdown.classList.toggle('hidden');
                    }
                });
            });

            // Fermer les dropdowns quand on clique ailleurs
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.bookMenuButton') && !e.target.closest('.bookDropdown')) {
                    bookDropdowns.forEach(dropdown => {
                        dropdown.classList.add('hidden');
                    });
                }
            });

            // Gestion des actions des livres
            const bookActions = document.querySelectorAll('.bookAction');
            bookActions.forEach(action => {
                action.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const actionType = this.getAttribute('data-action');
                    const bookCard = this.closest('.book');
                    const bookId = bookCard.querySelector('.bookMenuButton').getAttribute('data-book-id');
                    const bookTitle = bookCard.querySelector('h4').textContent;
                    const bookAuthor = bookCard.querySelector('p').textContent;

                    switch(actionType) {
                        case 'share':
                            if (navigator.share) {
                                navigator.share({
                                    title: bookTitle,
                                    text: `Découvrez ce livre: ${bookTitle} - ${bookAuthor}`,
                                    url: window.location.href
                                });
                            } else {
                                const shareText = `Découvrez ce livre: ${bookTitle} - ${bookAuthor}`;
                                navigator.clipboard.writeText(shareText).then(() => {
                                    alert('Lien copié dans le presse-papiers !');
                                });
                            }
                            break;
                        case 'details':
                            // Ouvrir la page Google Books spécifique du livre
                            const googleBooksUrl = bookCard.getAttribute('data-google-books-url');
                            if (googleBooksUrl && googleBooksUrl !== 'null') {
                                window.open(googleBooksUrl, '_blank');
                            } else {
                                // Fallback vers une recherche Google Books si l'URL spécifique n'est pas disponible
                                const fallbackUrl = `https://books.google.com/books?q=${encodeURIComponent(bookTitle)}`;
                                window.open(fallbackUrl, '_blank');
                            }
                            break;
                        case 'download':
                            alert(`Téléchargement de "${bookTitle}" en cours...`);
                            break;
                    }

                    // Fermer le dropdown après l'action
                    const dropdown = this.closest('.bookDropdown');
                    if (dropdown) {
                        dropdown.classList.add('hidden');
                    }
                });
            });

            // Empêcher la fermeture des dropdowns quand on clique à l'intérieur
            bookDropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
</x-app-layout>
