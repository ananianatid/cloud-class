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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Navigation du calendrier -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('calendrier', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
                               class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ \Carbon\Carbon::create($year, $month, 1)->locale('fr')->isoFormat('MMMM YYYY') }}
                            </h3>
                            <a href="{{ route('calendrier', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
                               class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        <a href="{{ route('calendrier') }}"
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Aujourd'hui
                        </a>
                    </div>
                </div>
            </div>

            <!-- Légende -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4">
                    <div class="flex items-center space-x-6 text-sm">
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-blue-500 rounded"></div>
                            <span class="text-gray-600">Cours</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-green-500 rounded"></div>
                            <span class="text-gray-600">Événements</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendrier -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- En-têtes des jours -->
                    <div class="grid grid-cols-7 gap-px bg-gray-200 mb-1">
                        @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $day)
                            <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-500">
                                {{ $day }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Grille du calendrier -->
                    <div class="grid grid-cols-7 gap-px bg-gray-200">
                        @foreach($calendar as $week)
                            @foreach($week as $day)
                                <div class="bg-white min-h-[120px] p-2 {{ !$day['isCurrentMonth'] ? 'bg-gray-50 text-gray-400' : '' }} {{ $day['isToday'] ? 'bg-blue-50 border-2 border-blue-300' : '' }}">
                                    <!-- Numéro du jour -->
                                    <div class="text-sm font-medium mb-2 {{ $day['isToday'] ? 'text-blue-600' : '' }}">
                                        {{ $day['date']->format('j') }}
                                    </div>

                                    <!-- Cours -->
                                    @foreach($day['cours']->take(2) as $cours)
                                        <div class="mb-1">
                                            <div class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded truncate"
                                                 title="{{ $cours->matiere->unite->nom ?? 'Cours' }} - {{ $cours->debut->format('H:i') }}-{{ $cours->fin->format('H:i') }}">
                                                <div class="font-medium">{{ $cours->debut->format('H:i') }}</div>
                                                <div class="truncate">{{ $cours->matiere->unite->nom ?? 'Cours' }}</div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Événements -->
                                    @foreach($day['evenements']->take(2) as $evenement)
                                        <div class="mb-1">
                                            <a href="{{ route('evenement.show', $evenement) }}"
                                               class="block text-xs px-2 py-1 rounded truncate hover:opacity-80 transition-opacity"
                                               style="background-color: {{ $evenement->couleur }}20; color: {{ $evenement->couleur }};"
                                               title="{{ $evenement->titre }}">
                                                @if($evenement->heure)
                                                    <div class="font-medium">{{ $evenement->heure->format('H:i') }}</div>
                                                @endif
                                                <div class="truncate">{{ $evenement->titre }}</div>
                                            </a>
                                        </div>
                                    @endforeach

                                    <!-- Indicateur s'il y a plus d'éléments -->
                                    @if($day['cours']->count() + $day['evenements']->count() > 4)
                                        <div class="text-xs text-gray-500 mt-1">
                                            +{{ $day['cours']->count() + $day['evenements']->count() - 4 }} autres
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Liste des événements du mois -->
            @if($evenements->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Événements du mois</h3>
                        <div class="space-y-3">
                            @foreach($evenements as $evenement)
                                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="w-3 h-3 rounded-full mt-2" style="background-color: {{ $evenement->couleur }}"></div>
                                    <div class="flex-1">
                                        <a href="{{ route('evenement.show', $evenement) }}"
                                           class="font-medium text-gray-900 hover:text-blue-600 transition-colors">
                                            {{ $evenement->titre }}
                                        </a>
                                        <div class="text-sm text-gray-600">
                                            {{ $evenement->formatted_date_time }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
