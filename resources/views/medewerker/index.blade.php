<x-layouts.app :title="__('Medewerkers')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <x-page-heading :title="__('Medewerkers')" />

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
                                'mondhygiënist' => 'purple',
                                'praktijkmanagement' => 'lime',
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
