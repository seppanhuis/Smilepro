<x-layouts.app :title="__('Gesprekken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <x-page-heading :title="__('Uw gesprekken')" />

        @if(count($medewerkers) === 0)
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded" role="alert">
                {{ __('Nog geen berichten, wacht tot uw dokter u een bericht stuurt.') }}
            </div>
        @else
            <x-table.index :headers="[
                __('Medewerker'),
                __('Actie')
            ]">
                @foreach($medewerkers as $m)
                    <x-table.row>
                        <x-table.cell bold>{{ $m->medewerker_naam }}</x-table.cell>
                        <x-table.cell>
                            <a href="{{ route('communicatie.gesprek', [$patientId, $m->medewerker_id]) }}" 
                               class="text-white bg-blue-600 hover:bg-blue-700 rounded px-3 py-1 text-sm">
                                {{ __('Bekijk gesprek') }}
                            </a>
                        </x-table.cell>
                    </x-table.row>
                @endforeach
            </x-table.index>
        @endif
    </div>
</x-layouts.app>
