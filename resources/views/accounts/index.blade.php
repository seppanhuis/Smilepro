<x-layouts.app :title="__('Accounts')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <x-page-heading :title="__('Accounts')" />

        <x-table.index :headers="[
            __('Gebruikersnaam'),
            __('Email'),
            __('Rol'),
            __('Status'),
            __('Laatst ingelogd'),
            __('Aangemaakt op')
        ]">
            @forelse($accounts as $account)
                <x-table.row>
                    <x-table.cell bold>{{ $account->gebruikersnaam }}</x-table.cell>
                    <x-table.cell>{{ $account->email }}</x-table.cell>
                    <x-table.cell>
                        <x-badge
                            :color="$account->rol_naam === 'praktijkmanagement' ? 'lime' : 'zinc'"
                            :text="$account->rol_naam"
                        />
                    </x-table.cell>
                    <x-table.cell>
                        <x-badge
                            :color="$account->is_actief ? 'green' : 'red'"
                            :text="$account->is_actief ? __('Actief') : __('Inactief')"
                        />
                    </x-table.cell>
                    <x-table.cell>
                        {{ $account->ingelogd ? $account->ingelogd->format('d-m-Y H:i') : '-' }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $account->created_at->format('d-m-Y H:i') }}
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.empty :colspan="6" :message="__('Geen accounts gevonden.')" />
            @endforelse
        </x-table.index>
    </div>
</x-layouts.app>
