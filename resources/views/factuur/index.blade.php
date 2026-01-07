<x-layouts.app :title="__('Facturen')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="flex items-center justify-between">
            <x-page-heading :title="__('Facturen')" />
            <a
                href="{{ route('factuur.create') }}"
                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Nieuwe Factuur') }}
            </a>
        </div>

        <x-table.index :headers="[
            __('Factuurnummer'),
            __('Patiënt'),
            __('Datum'),
            __('Bedrag'),
            __('Status'),
            __('Behandeling'),
        ]">
            @forelse($facturen as $factuur)
                <x-table.row>
                    <x-table.cell bold>{{ $factuur->nummer }}</x-table.cell>
                    <x-table.cell>
                        @if($factuur->patient && $factuur->patient->persoon)
                            {{ $factuur->patient->persoon->voornaam }}
                            @if($factuur->patient->persoon->tussenvoegsel)
                                {{ $factuur->patient->persoon->tussenvoegsel }}
                            @endif
                            {{ $factuur->patient->persoon->achternaam }}
                        @else
                            -
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        {{ $factuur->datum->format('d-m-Y') }}
                    </x-table.cell>
                    <x-table.cell>
                        € {{ number_format($factuur->bedrag, 2, ',', '.') }}
                    </x-table.cell>
                    <x-table.cell>
                        <x-badge
                            :color="match($factuur->status) {
                                'Betaald' => 'green',
                                'Verzonden' => 'blue',
                                'Niet-Verzonden' => 'yellow',
                                'Onbetaald' => 'red',
                                default => 'zinc'
                            }"
                            :text="$factuur->status"
                        />
                    </x-table.cell>
                    <x-table.cell>
                        @if($factuur->behandeling)
                            {{ $factuur->behandeling->behandeling_type }}
                        @else
                            -
                        @endif
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.empty :colspan="6" :message="__('Geen facturen gevonden.')" />
            @endforelse
        </x-table.index>
    </div>
</x-layouts.app>
