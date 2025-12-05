<x-layouts.app :title="__('Beschikbaarheid')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <x-page-heading :title="__('Beschikbaarheid')">
            <a href="{{ route('beschikbaarheid.create') }}" class="inline-flex items-center rounded-md bg-lime-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-lime-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-lime-600">
                {{ __('Toevoegen') }}
            </a>
        </x-page-heading>

        @if(session('success'))
            <div class="rounded-md bg-green-50 p-4 dark:bg-green-900/20">
                <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        @endif

        <x-table.index :headers="[
            __('Medewerker'),
            __('Van datum'),
            __('Tot datum'),
            __('Van tijd'),
            __('Tot tijd'),
            __('Status'),
            __('Opmerking'),
            __('Acties')
        ]">
            @forelse($beschikbaarheden as $beschikbaarheid)
                <x-table.row>
                    <x-table.cell bold>{{ $beschikbaarheid->user->gebruikersnaam ?? 'Onbekend' }}</x-table.cell>
                    <x-table.cell>{{ $beschikbaarheid->datum_vanaf->format('d-m-Y') }}</x-table.cell>
                    <x-table.cell>{{ $beschikbaarheid->datum_tot_met->format('d-m-Y') }}</x-table.cell>
                    <x-table.cell>{{ $beschikbaarheid->tijd_vanaf }}</x-table.cell>
                    <x-table.cell>{{ $beschikbaarheid->tijd_tot_met }}</x-table.cell>
                    <x-table.cell>
                        <x-badge
                            :color="match($beschikbaarheid->status) {
                                'Aanwezig' => 'green',
                                'Afwezig' => 'red',
                                'Verlof' => 'blue',
                                'Ziek' => 'yellow',
                                default => 'zinc'
                            }"
                            :text="$beschikbaarheid->status"
                        />
                    </x-table.cell>
                    <x-table.cell>{{ $beschikbaarheid->opmerking ?? '-' }}</x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-2">
                            @php
                                $canEdit = auth()->user()->rol_naam === 'Praktijkmanagement' || $beschikbaarheid->user_id === auth()->id();
                            @endphp
                            @if($canEdit)
                                <a href="{{ route('beschikbaarheid.edit', $beschikbaarheid) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ __('Bewerken') }}
                                </a>
                                <form action="{{ route('beschikbaarheid.destroy', $beschikbaarheid) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je dit wilt verwijderen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        {{ __('Verwijderen') }}
                                    </button>
                                </form>
                            @else
                                <span class="text-zinc-400">-</span>
                            @endif
                        </div>
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.empty :colspan="8" :message="__('Geen beschikbaarheid gevonden.')" />
            @endforelse
        </x-table.index>
    </div>
</x-layouts.app>
