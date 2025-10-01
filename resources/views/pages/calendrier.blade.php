<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-light text-xl text-gray-800 leading-tight">
                {{ __('Calendrier') }}
            </h2>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('filament.admin.resources.evenements.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajouter un événement
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Navigation du mois/année -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                        <!-- Navigation par mois -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('calendrier', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
                               class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ \Carbon\Carbon::create($year, $month, 1)->locale('fr')->isoFormat('MMMM YYYY') }}
                            </h3>
                            <a href="{{ route('calendrier', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
                               class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- Sélecteur d'année -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                            <label for="year-select" class="text-sm font-medium text-gray-700">Année :</label>
                            <div class="flex items-center space-x-2">
                                <div class="relative">
                                    <select id="year-select"
                                            class="border border-gray-300 rounded-full px-6 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-32 pr-12 appearance-none bg-white bg-no-repeat bg-right bg-[length:16px] bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBzdHJva2Utd2lkdGg9IjIiIGQ9Ik0xOSA5bC03IDctNy03IiBzdHJva2U9IiM2QjcyODAiLz4KPC9zdmc+')]"
                                            onchange="changeYear(this.value)">
                                        @foreach($years as $yearOption)
                                            <option value="{{ $yearOption }}" {{ $yearOption == $year ? 'selected' : '' }}>
                                                {{ $yearOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <a href="{{ route('calendrier') }}"
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors text-sm whitespace-nowrap">
                                    Aujourd'hui
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Liste des événements -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl"
                 x-data="{
                     currentIndex: 0,
                     touchStartX: 0,
                     touchEndX: 0,
                     handleTouchStart(e) {
                         this.touchStartX = e.changedTouches[0].screenX;
                     },
                     handleTouchEnd(e) {
                         this.touchEndX = e.changedTouches[0].screenX;
                         this.handleSwipe();
                     },
                     handleSwipe() {
                         const swipeThreshold = 50;
                         const diff = this.touchStartX - this.touchEndX;

                         if (Math.abs(diff) > swipeThreshold) {
                             if (diff > 0) {
                                 // Swipe gauche - mois suivant
                                 window.location.href = '{{ route('calendrier', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}';
                             } else {
                                 // Swipe droite - mois précédent
                                 window.location.href = '{{ route('calendrier', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}';
                             }
                         }
                     }
                 }"
                 @touchstart="handleTouchStart"
                 @touchend="handleTouchEnd">

                <div class="p-6">
                    @if($evenements->count() > 0)
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-semibold text-gray-900">
                                Événements de {{ \Carbon\Carbon::create($year, $month, 1)->locale('fr')->isoFormat('MMMM YYYY') }}
                            </h3>
                            <span class="text-sm text-gray-500 bg-gray-100 px-4 py-2 rounded-full">
                                {{ $evenements->count() }} événement{{ $evenements->count() > 1 ? 's' : '' }}
                            </span>
                        </div>

                        <div class="space-y-3 sm:space-y-4">
                            @foreach($evenements as $evenement)
                                <div class="border border-gray-200 rounded-2xl p-3 sm:p-4 hover:shadow-md transition-shadow duration-200">
                                    <div class="flex items-start space-x-3 sm:space-x-4">
                                        <!-- Indicateur de couleur -->
                                        <div class="flex-shrink-0">
                                            <div class="w-3 h-3 sm:w-4 sm:h-4 rounded-full mt-1" style="background-color: {{ $evenement->couleur }}"></div>
                                        </div>

                                        <!-- Contenu de l'événement -->
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('evenement.show', $evenement) }}"
                                               class="block group">
                                                <h4 class="text-base sm:text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors mb-2">
                                                    {{ $evenement->titre }}
                                                </h4>

                                                <div class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-4 text-sm text-gray-600 mb-3">
                                                    <div class="flex items-center space-x-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <span>{{ $evenement->date->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</span>
                                                    </div>
                                                    @if($evenement->heure)
                                                        <div class="flex items-center space-x-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            <span>{{ $evenement->heure->format('H:i') }}</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Aperçu du contenu -->
                                                <div class="text-gray-600 text-sm line-clamp-2">
                                                    {!! \Illuminate\Support\Str::markdown(\Illuminate\Support\Str::limit(strip_tags($evenement->corps), 150)) !!}
                                                </div>
                                            </a>
                                        </div>

                                        <!-- Flèche -->
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Message quand aucun événement -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun événement</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Aucun événement prévu pour {{ \Carbon\Carbon::create($year, $month, 1)->locale('fr')->isoFormat('MMMM YYYY') }}.
                            </p>
                            @if(auth()->user()->role === 'admin')
                                <div class="mt-6">
                                    <a href="{{ route('filament.admin.resources.evenements.create') }}"
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Créer le premier événement
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeYear(year) {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('year', year);
            window.location.href = currentUrl.toString();
        }
    </script>
</x-app-layout>
