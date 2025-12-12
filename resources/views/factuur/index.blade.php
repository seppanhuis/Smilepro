<x-layouts.app :title="__('Facturen')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <x-page-heading :title="__('Facturen')" />

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
