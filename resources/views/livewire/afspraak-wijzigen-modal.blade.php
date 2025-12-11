<div>
    @if ($toonModal)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center">

            <div class="bg-white p-5 rounded shadow-xl w-96">
                <h2 class="text-xl font-bold">Afspraak wijzigen</h2>

                @if ($foutmelding)
                    <p class="text-red-600 mt-2">{{ $foutmelding }}</p>
                @endif

                @if (!$tijdsloten->isEmpty())
                    <select wire:model="geselecteerdTijdslotId" class="border p-2 w-full mt-3">
                        <option value="">Kies een nieuw tijdslot</option>
                        @foreach ($tijdsloten as $slot)
                            <option value="{{ $slot->id }}">
                                {{ $slot->starttijd }} - {{ $slot->eindtijd }}
                            </option>
                        @endforeach
                    </select>

                    <button 
                        wire:click="wijzigAfspraak"
                        class="bg-green-500 text-white px-4 py-2 rounded mt-3"
                    >
                        Opslaan
                    </button>
                @endif

                <button 
                    wire:click="$set('toonModal', false)" 
                    class="mt-3 underline"
                >
                    Annuleren
                </button>
            </div>

        </div>
    @endif
</div>
