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

        <x-table.index :headers="[
            __('Gebruikersnaam'),
            __('Email'),
            __('Rol'),
            __('Status'),
            __('Laatst ingelogd'),
            __('Aangemaakt op')
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
                        {{ $medewerker->ingelogd ? $medewerker->ingelogd->format('d-m-Y H:i') : '-' }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $medewerker->created_at->format('d-m-Y H:i') }}
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.empty :colspan="6" :message="__('Geen medewerkers gevonden.')" />
            @endforelse
        </x-table.index>
    </div>
</x-layouts.app>
