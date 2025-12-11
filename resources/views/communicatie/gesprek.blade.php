<x-layouts.app :title="__('Gesprek')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <x-page-heading :title="__('Gesprek met medewerker')" />

        <!-- Terug link met correct patientId -->
        <a href="{{ route('communicatie.index', $patientId) }}" 
           class="text-blue-600 hover:underline mb-4 inline-block">
            ← {{ __('Terug naar gesprekken') }}
        </a>

        @if(count($berichten) === 0)
            <x-alert>
                {{ __('Er zijn nog geen berichten tussen u en deze medewerker.') }}
            </x-alert>
        @else
            <div class="space-y-4">
                @foreach($berichten as $b)
                    <!-- Bepaal of bericht van medewerker of van patiënt is -->
                    @php
                        $isMedewerker = $b->afzender_id == $medewerkerId;
                    @endphp

                    <div class="p-3 rounded shadow
                                {{ $isMedewerker ? 'bg-gray-100 text-black' : 'bg-blue-600 text-white' }}">
                        <div class="text-sm mb-1">
                            <strong>
                                {{ $isMedewerker ? __('Medewerker') : __('U') }}
                            </strong>
                            - {{ \Carbon\Carbon::parse($b->datum)->format('d-m-Y H:i') }}
                        </div>
                        <p>{{ $b->bericht }}</p>
                        @if(property_exists($b, 'opmerking') && $b->opmerking)
                            <small class="italic text-gray-500">{{ $b->opmerking }}</small>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
