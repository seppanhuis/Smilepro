<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Medewerker Beheer</h1>
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

        <!-- Zoek en filter -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Zoeken</label>
                <input
                    type="text"
                    id="search"
                    wire:model.live="search"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Zoek op naam..."
                    aria-label="Zoek medewerker"
                >
            </div>
            <div>
                <label for="filterActief" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select
                    id="filterActief"
                    wire:model.live="filterActief"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    aria-label="Filter op status"
                >
                    <option value="all">Alle</option>
                    <option value="actief">Actief</option>
                    <option value="inactief">Inactief</option>
                </select>
            </div>
        </div>

        <!-- Medewerkers tabel -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Naam
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
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
                    @forelse ($medewerkers as $medewerker)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $medewerker->full_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    {{ $medewerker->persoon->user->email ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $medewerker->medewerker_type }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $medewerker->is_actief ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $medewerker->is_actief ? 'Actief' : 'Inactief' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button
                                    wire:click="editMedewerker({{ $medewerker->id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-3"
                                    aria-label="Wijzig medewerker {{ $medewerker->full_name }}"
                                >
                                    Wijzigen
                                </button>
                                <button
                                    wire:click="confirmDelete({{ $medewerker->id }})"
                                    class="text-red-600 hover:text-red-900"
                                    aria-label="Verwijder medewerker {{ $medewerker->full_name }}"
                                >
                                    Verwijderen
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Geen medewerkers gevonden
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginering -->
        <div class="mt-4">
            {{ $medewerkers->links() }}
        </div>
    </div>

    <!-- Wijzig Modal -->
    @if ($showEditModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit.prevent="updateMedewerker">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                Medewerker wijzigen
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Voornaam -->
                                <div>
                                    <label for="voornaam" class="block text-sm font-medium text-gray-700">
                                        Voornaam <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="voornaam"
                                        wire:model="voornaam"
                                        required
                                        minlength="2"
                                        maxlength="100"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('voornaam') border-red-500 @enderror"
                                    >
                                    @error('voornaam') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Tussenvoegsel -->
                                <div>
                                    <label for="tussenvoegsel" class="block text-sm font-medium text-gray-700">
                                        Tussenvoegsel
                                    </label>
                                    <input
                                        type="text"
                                        id="tussenvoegsel"
                                        wire:model="tussenvoegsel"
                                        maxlength="50"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                </div>

                                <!-- Achternaam -->
                                <div>
                                    <label for="achternaam" class="block text-sm font-medium text-gray-700">
                                        Achternaam <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="achternaam"
                                        wire:model="achternaam"
                                        required
                                        minlength="2"
                                        maxlength="100"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('achternaam') border-red-500 @enderror"
                                    >
                                    @error('achternaam') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        wire:model="email"
                                        required
                                        maxlength="255"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-500 @enderror"
                                    >
                                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Geboortedatum -->
                                <div>
                                    <label for="geboortedatum" class="block text-sm font-medium text-gray-700">
                                        Geboortedatum <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        id="geboortedatum"
                                        wire:model="geboortedatum"
                                        required
                                        max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('geboortedatum') border-red-500 @enderror"
                                    >
                                    @error('geboortedatum') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Medewerker Type -->
                                <div>
                                    <label for="medewerker_type" class="block text-sm font-medium text-gray-700">
                                        Type <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="medewerker_type"
                                        wire:model="medewerker_type"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('medewerker_type') border-red-500 @enderror"
                                    >
                                        <option value="">Selecteer type</option>
                                        <option value="Tandarts">Tandarts</option>
                                        <option value="Mondhygiënist">Mondhygiënist</option>
                                        <option value="Assistent">Assistent</option>
                                        <option value="Orthodontist">Orthodontist</option>
                                    </select>
                                    @error('medewerker_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Specialisatie -->
                                <div>
                                    <label for="specialisatie" class="block text-sm font-medium text-gray-700">
                                        Specialisatie
                                    </label>
                                    <input
                                        type="text"
                                        id="specialisatie"
                                        wire:model="specialisatie"
                                        maxlength="255"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
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
                                Medewerker opslaan
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
                                    Medewerker verwijderen
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Weet u zeker dat u deze medewerker wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            wire:click="deleteMedewerker"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Medewerker verwijderen
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
    // Real-time validatie voor email
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(this.value)) {
                this.setCustomValidity('Voer een geldig email adres in');
            } else {
                this.setCustomValidity('');
            }
        });
    }

    // Real-time validatie voor datum
    const datumInput = document.getElementById('geboortedatum');
    if (datumInput) {
        datumInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            if (selectedDate >= today) {
                this.setCustomValidity('Geboortedatum moet in het verleden liggen');
            } else {
                this.setCustomValidity('');
            }
        });
    }
});
</script>
