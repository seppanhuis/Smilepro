<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Beschikbaarheid Beheer</h1>
        </div>

        <!-- Flash berichten -->
        @if (session()->has('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p class="font-bold">Succes</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Fout</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Filter -->
        <div class="mb-6">
            <label for="filterMedewerker" class="block text-sm font-medium text-gray-700 mb-2">
                Filter op medewerker
            </label>
            <select
                id="filterMedewerker"
                wire:model.live="filterMedewerker"
                class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                aria-label="Filter op medewerker"
            >
                <option value="all">Alle medewerkers</option>
                @foreach ($medewerkers as $medewerker)
                    <option value="{{ $medewerker->id }}">
                        {{ $medewerker->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Beschikbaarheid tabel -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Medewerker
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Datum
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tijd
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acties
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($beschikbaarheden as $beschikbaarheid)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $beschikbaarheid->medewerker->full_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $beschikbaarheid->datum_vanaf->format('d-m-Y') }} -
                                    {{ $beschikbaarheid->datum_tot_met->format('d-m-Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $beschikbaarheid->tijd_vanaf }} - {{ $beschikbaarheid->tijd_tot_met }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($beschikbaarheid->status === 'Beschikbaar') bg-green-100 text-green-800
                                    @elseif($beschikbaarheid->status === 'Vakantie') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $beschikbaarheid->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button
                                    wire:click="editBeschikbaarheid({{ $beschikbaarheid->id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-3"
                                    aria-label="Wijzig beschikbaarheid"
                                >
                                    Wijzigen
                                </button>
                                <button
                                    wire:click="confirmDelete({{ $beschikbaarheid->id }})"
                                    class="text-red-600 hover:text-red-900"
                                    aria-label="Verwijder beschikbaarheid"
                                >
                                    Verwijderen
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Geen beschikbaarheid gevonden
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginering -->
        <div class="mt-4">
            {{ $beschikbaarheden->links() }}
        </div>
    </div>

    <!-- Wijzig Modal -->
    @if ($showEditModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit.prevent="updateBeschikbaarheid" id="beschikbaarheidForm">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                Beschikbaarheid wijzigen
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Medewerker -->
                                <div class="md:col-span-2">
                                    <label for="medewerker_id" class="block text-sm font-medium text-gray-700">
                                        Medewerker <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="medewerker_id"
                                        wire:model="medewerker_id"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('medewerker_id') border-red-500 @enderror"
                                    >
                                        <option value="">Selecteer medewerker</option>
                                        @foreach ($medewerkers as $medewerker)
                                            <option value="{{ $medewerker->id }}">{{ $medewerker->full_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('medewerker_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Datum vanaf -->
                                <div>
                                    <label for="datum_vanaf" class="block text-sm font-medium text-gray-700">
                                        Start datum <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        id="datum_vanaf"
                                        wire:model="datum_vanaf"
                                        required
                                        min="{{ date('Y-m-d') }}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('datum_vanaf') border-red-500 @enderror"
                                    >
                                    @error('datum_vanaf') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Datum tot met -->
                                <div>
                                    <label for="datum_tot_met" class="block text-sm font-medium text-gray-700">
                                        Eind datum <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        id="datum_tot_met"
                                        wire:model="datum_tot_met"
                                        required
                                        min="{{ date('Y-m-d') }}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('datum_tot_met') border-red-500 @enderror"
                                    >
                                    @error('datum_tot_met') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Tijd vanaf -->
                                <div>
                                    <label for="tijd_vanaf" class="block text-sm font-medium text-gray-700">
                                        Start tijd <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="time"
                                        id="tijd_vanaf"
                                        wire:model="tijd_vanaf"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('tijd_vanaf') border-red-500 @enderror"
                                    >
                                    @error('tijd_vanaf') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Tijd tot met -->
                                <div>
                                    <label for="tijd_tot_met" class="block text-sm font-medium text-gray-700">
                                        Eind tijd <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="time"
                                        id="tijd_tot_met"
                                        wire:model="tijd_tot_met"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('tijd_tot_met') border-red-500 @enderror"
                                    >
                                    @error('tijd_tot_met') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="status"
                                        wire:model="status"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('status') border-red-500 @enderror"
                                    >
                                        <option value="">Selecteer status</option>
                                        <option value="Beschikbaar">Beschikbaar</option>
                                        <option value="Niet beschikbaar">Niet beschikbaar</option>
                                        <option value="Vakantie">Vakantie</option>
                                    </select>
                                    @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Is Actief -->
                                <div class="flex items-center">
                                    <input
                                        type="checkbox"
                                        id="is_actief"
                                        wire:model="is_actief"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    >
                                    <label for="is_actief" class="ml-2 block text-sm text-gray-900">
                                        Actief
                                    </label>
                                </div>

                                <!-- Opmerking -->
                                <div class="md:col-span-2">
                                    <label for="opmerking" class="block text-sm font-medium text-gray-700">
                                        Opmerking
                                    </label>
                                    <textarea
                                        id="opmerking"
                                        wire:model="opmerking"
                                        rows="3"
                                        maxlength="1000"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button
                                type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                Beschikbaarheid opslaan
                            </button>
                            <button
                                type="button"
                                wire:click="closeModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                Annuleren
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Verwijder Modal -->
    @if ($showDeleteModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Beschikbaarheid verwijderen
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Weet u zeker dat u deze beschikbaarheid wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            wire:click="deleteBeschikbaarheid"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Beschikbaarheid verwijderen
                        </button>
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Annuleren
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Client-side validatie script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validatie voor datum bereik
    const datumVanaf = document.getElementById('datum_vanaf');
    const datumTotMet = document.getElementById('datum_tot_met');

    if (datumVanaf && datumTotMet) {
        // Update min datum van eind datum wanneer start datum verandert
        datumVanaf.addEventListener('change', function() {
            datumTotMet.min = this.value;

            // Valideer of eind datum nog steeds geldig is
            if (datumTotMet.value && datumTotMet.value < this.value) {
                datumTotMet.setCustomValidity('Eind datum moet na of gelijk aan start datum zijn');
            } else {
                datumTotMet.setCustomValidity('');
            }
        });

        datumTotMet.addEventListener('change', function() {
            if (datumVanaf.value && this.value < datumVanaf.value) {
                this.setCustomValidity('Eind datum moet na of gelijk aan start datum zijn');
            } else {
                this.setCustomValidity('');
            }
        });
    }

    // Validatie voor tijd bereik
    const tijdVanaf = document.getElementById('tijd_vanaf');
    const tijdTotMet = document.getElementById('tijd_tot_met');

    if (tijdVanaf && tijdTotMet) {
        function validateTijden() {
            if (tijdVanaf.value && tijdTotMet.value) {
                const startTijd = tijdVanaf.value;
                const eindTijd = tijdTotMet.value;

                if (eindTijd <= startTijd) {
                    tijdTotMet.setCustomValidity('Eind tijd moet na start tijd zijn');
                } else {
                    tijdTotMet.setCustomValidity('');
                }
            }
        }

        tijdVanaf.addEventListener('change', validateTijden);
        tijdTotMet.addEventListener('change', validateTijden);
    }

    // Voorkom submit als er validatie fouten zijn
    const form = document.getElementById('beschikbaarheidForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();

                // Markeer alle invalide velden
                const invalidFields = this.querySelectorAll(':invalid');
                invalidFields.forEach(field => {
                    field.classList.add('border-red-500');
                });
            }
        });
    }
});
</script>
