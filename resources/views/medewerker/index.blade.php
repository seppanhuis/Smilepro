<x-layouts.app :title="__('Medewerkers')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="flex items-center justify-between">
            <x-page-heading :title="__('Medewerkers')" />
            <a
                href="{{ route('medewerker.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Medewerker Toevoegen
            </a>
        </div>

        {{-- Succesmelding --}}
        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Foutmelding --}}
        @if (session('error'))
            <div class="rounded-md bg-red-50 p-4" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <x-table.index :headers="[
            __('Gebruikersnaam'),
            __('Email'),
            __('Rol'),
            __('Status'),
            __('Aangemaakt op'),
            __('Acties')
        ]">
            @forelse($medewerkers as $medewerker)
                <x-table.row>
                    <x-table.cell bold>{{ $medewerker->gebruikersnaam }}</x-table.cell>
                    <x-table.cell>{{ $medewerker->email }}</x-table.cell>
                    <x-table.cell>
                        <x-badge
                            :color="match($medewerker->rol_naam) {
                                'tandarts' => 'blue',
                                'Tandarts' => 'blue',
                                'mondhygiënist' => 'purple',
                                'Mondhygiënist' => 'purple',
                                'praktijkmanagement' => 'lime',
                                'Praktijkmanagement' => 'lime',
                                'Assistent' => 'zinc',
                                'assistent' => 'zinc',
                                default => 'zinc'
                            }"
                            :text="$medewerker->rol_naam"
                        />
                    </x-table.cell>
                    <x-table.cell>
                        <x-badge
                            :color="$medewerker->is_actief ? 'green' : 'red'"
                            :text="$medewerker->is_actief ? __('Actief') : __('Inactief')"
                        />
                    </x-table.cell>
                    <x-table.cell>
                        {{ $medewerker->created_at->format('d-m-Y H:i') }}
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-2">
                            @php
                                // Haal de medewerker op via de relatie
                                $medewerkerRecord = \App\Models\Medewerker::whereHas('persoon', function($q) use ($medewerker) {
                                    $q->where('gebruiker_id', $medewerker->id);
                                })->first();
                            @endphp

                            @if($medewerkerRecord)
                                <a href="{{ route('medewerker.edit', $medewerkerRecord) }}"
                                   class="inline-flex items-center gap-1 rounded-md bg-blue-50 dark:bg-blue-900/30 px-3 py-1.5 text-sm font-medium text-blue-700 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition border border-blue-200 dark:border-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ __('Bewerken') }}
                                </a>
                                <form action="{{ route('medewerker.destroy', $medewerkerRecord) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Weet je zeker dat je deze medewerker wilt verwijderen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 rounded-md bg-red-50 dark:bg-red-900/30 px-3 py-1.5 text-sm font-medium text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 transition border border-red-200 dark:border-red-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        {{ __('Verwijderen') }}
                                    </button>
                                </form>
                            @else
                                <span class="text-zinc-400 dark:text-zinc-600 text-sm">{{ __('Geen acties') }}</span>
                            @endif
                        </div>
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.empty :colspan="6" :message="__('Geen medewerkers gevonden.')" />
            @endforelse
        </x-table.index>
    </div>
</x-layouts.app>
