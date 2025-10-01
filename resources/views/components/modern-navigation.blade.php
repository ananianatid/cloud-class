<div class="flex justify-center items-center p-4">
    <!-- Navigation principale -->
    <nav class="bg-white rounded-full py-2 px-2 w-max shadow-md flex ">
        <!-- Logo - toujours visible -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-100 rounded-full transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
            </svg>
        </a>

        <!-- Liens de navigation - visibles sur PC, cachés sur mobile -->
        <div class="hidden md:flex  [&>*]:mx-1">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-100 rounded-full transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : '' }}">
                Accueil
            </a>
            <a href="{{ route('semestres') }}" class="px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-100 rounded-full transition-all duration-200 {{ request()->routeIs('semestres') ? 'bg-blue-50 text-blue-600' : '' }}">
                Semestre
            </a>
            <a href="{{ route('emplois-du-temps') }}" class="px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-100 rounded-full transition-all duration-200 {{ request()->routeIs('emplois-du-temps') ? 'bg-blue-50 text-blue-600' : '' }}">
                Emploi du temps
            </a>
            <a href="{{ route('bibliotheque') }}" class="px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-100 rounded-full transition-all duration-200 {{ request()->routeIs('bibliotheque') ? 'bg-blue-50 text-blue-600' : '' }}">
                Bibliothèque
            </a>
        </div>

        <!-- Page actuelle - visible uniquement sur mobile -->
        <div class="md:hidden flex items-center">
            @if(request()->routeIs('dashboard'))
                <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-full">Accueil</span>
            @elseif(request()->routeIs('semestres'))
                <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-full">Semestre</span>
            @elseif(request()->routeIs('emplois-du-temps'))
                <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-full">Emploi du temps</span>
            @elseif(request()->routeIs('bibliotheque'))
                <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-full">Bibliothèque</span>
            @else
                <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-full">Menu</span>
            @endif
        </div>
    </nav>

    <!-- Menu des trois points avec dropdown -->
    <div class="relative ml-4 ">
        <nav class="bg-white shadow-md rounded-full py-2 px-2 box flex items-center justify-center">
            <button id="menuButton" class="p-2 text-gray-700 hover:text-blue-600 hover:bg-gray-100 rounded-full transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </button>
        </nav>

        <!-- Dropdown Menu -->
        <div id="dropdownMenu" class="bg-white dropdown absolute right-0 mt-2 w-64 rounded-3xl  shadow-lg border border-gray-200 z-50 hidden backdrop-blur-xs">
            <div class="p-4 ">
                <!-- Profil utilisateur -->
                <div class="flex items-center space-x-3 mb-4 pb-4 border-b border-gray-200">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">{{ substr(Auth::user()->name, 0, 2) }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                    </div>
                </div>

                <!-- Options du menu -->
                <div class="space-y-2">
                    <!-- Navigation mobile - visible uniquement sur mobile -->
                    <div class="md:hidden space-y-2 pb-2 border-b border-gray-200">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-full transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            <span>Accueil</span>
                        </a>
                        <a href="{{ route('semestres') }}" class="flex items-center space-x-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-full transition-colors duration-200 {{ request()->routeIs('semestres') ? 'bg-blue-50 text-blue-600' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                            <span>Semestre</span>
                        </a>
                        <a href="{{ route('emplois-du-temps') }}" class="flex items-center space-x-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-full transition-colors duration-200 {{ request()->routeIs('emplois-du-temps') ? 'bg-blue-50 text-blue-600' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5" />
                            </svg>
                            <span>Emploi du temps</span>
                        </a>
                        <a href="{{ route('bibliotheque') }}" class="flex items-center space-x-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-full transition-colors duration-200 {{ request()->routeIs('bibliotheque') ? 'bg-blue-50 text-blue-600' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                            <span>Bibliothèque</span>
                        </a>
                    </div>

                    <a href="{{ route('profile.show') }}" class="flex items-center space-x-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-full transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        <span>Mon Profil</span>
                    </a>

                    <div class="border-t border-gray-200 pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center space-x-3 px-3 py-2 text-red-600 hover:bg-red-50  rounded-full transition-colors duration-200 w-full text-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                </svg>
                                <span>Se déconnecter</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuButton = document.getElementById('menuButton');
    const dropdownMenu = document.getElementById('dropdownMenu');

    menuButton.addEventListener('click', function() {
        dropdownMenu.classList.toggle('hidden');
    });

    // Fermer le dropdown quand on clique à l'extérieur
    document.addEventListener('click', function(event) {
        if (!menuButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });
});
</script>
