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

        @if(session('error'))
            <div class="rounded-md bg-red-50 p-4 dark:bg-red-900/20">
                <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
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
                                <a href="{{ route('beschikbaarheid.edit', $beschikbaarheid) }}" class="inline-flex items-center gap-1 rounded-md bg-blue-50 dark:bg-blue-900/30 px-3 py-1.5 text-sm font-medium text-blue-700 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition border border-blue-200 dark:border-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ __('Bewerken') }}
                                </a>
                                <form action="{{ route('beschikbaarheid.destroy', $beschikbaarheid) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je dit wilt verwijderen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-red-50 dark:bg-red-900/30 px-3 py-1.5 text-sm font-medium text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 transition border border-red-200 dark:border-red-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        {{ __('Verwijderen') }}
                                    </button>
                                </form>
                            @else
                                <span class="text-zinc-400 dark:text-zinc-600">-</span>
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
