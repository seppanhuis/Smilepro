<x-layouts.app :title="__('Medewerker Wijzigen')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <x-page-heading :title="__('Medewerker Wijzigen')" />

        {{-- Foutmeldingen --}}
        @if (session('error'))
            <div class="rounded-md bg-red-50 p-4" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Validatie fouten --}}
        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Er zijn enkele fouten in het formulier:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Formulier --}}
        <div class="bg-white shadow-sm rounded-lg">
            <form
                action="{{ route('medewerker.update', $medewerker) }}"
                method="POST"
                id="medewerkerForm"
                class="space-y-6 p-6"
                novalidate
            >
                @csrf
                @method('PUT')

                {{-- Persoonsgegevens Sectie --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Persoonsgegevens</h3>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        {{-- Voornaam --}}
                        <div>
                            <label for="voornaam" class="block text-sm font-medium text-gray-700">
                                Voornaam <span class="text-red-600">*</span>
                            </label>
                            <input
                                type="text"
                                name="voornaam"
                                id="voornaam"
                                value="{{ old('voornaam', $medewerker->persoon->voornaam) }}"
                                required
                                minlength="2"
                                maxlength="100"
                                pattern="[a-zA-Z\s-]+"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('voornaam') border-red-300 @enderror"
                                aria-describedby="voornaam-error"
                            >
                            @error('voornaam')
                                <p class="mt-2 text-sm text-red-600" id="voornaam-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tussenvoegsel --}}
                        <div>
                            <label for="tussenvoegsel" class="block text-sm font-medium text-gray-700">
                                Tussenvoegsel
                            </label>
                            <input
                                type="text"
                                name="tussenvoegsel"
                                id="tussenvoegsel"
                                value="{{ old('tussenvoegsel', $medewerker->persoon->tussenvoegsel) }}"
                                maxlength="50"
                                pattern="[a-zA-Z\s]*"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('tussenvoegsel') border-red-300 @enderror"
                                aria-describedby="tussenvoegsel-error"
                            >
                            @error('tussenvoegsel')
                                <p class="mt-2 text-sm text-red-600" id="tussenvoegsel-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Achternaam --}}
                        <div>
                            <label for="achternaam" class="block text-sm font-medium text-gray-700">
                                Achternaam <span class="text-red-600">*</span>
                            </label>
                            <input
                                type="text"
                                name="achternaam"
                                id="achternaam"
                                value="{{ old('achternaam', $medewerker->persoon->achternaam) }}"
                                required
                                minlength="2"
                                maxlength="100"
                                pattern="[a-zA-Z\s-]+"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('achternaam') border-red-300 @enderror"
                                aria-describedby="achternaam-error"
                            >
                            @error('achternaam')
                                <p class="mt-2 text-sm text-red-600" id="achternaam-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                E-mailadres <span class="text-red-600">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email', $medewerker->persoon->gebruiker->email) }}"
                                required
                                maxlength="255"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('email') border-red-300 @enderror"
                                aria-describedby="email-error"
                            >
                            @error('email')
                                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Geboortedatum --}}
                        <div>
                            <label for="geboortedatum" class="block text-sm font-medium text-gray-700">
                                Geboortedatum <span class="text-red-600">*</span>
                            </label>
                            <input
                                type="date"
                                name="geboortedatum"
                                id="geboortedatum"
                                value="{{ old('geboortedatum', $medewerker->persoon->geboortedatum?->format('Y-m-d')) }}"
                                required
                                max="{{ date('Y-m-d') }}"
                                min="1920-01-01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('geboortedatum') border-red-300 @enderror"
                                aria-describedby="geboortedatum-error"
                            >
                            @error('geboortedatum')
                                <p class="mt-2 text-sm text-red-600" id="geboortedatum-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Medewerkergegevens Sectie --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Medewerkergegevens</h3>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        {{-- Medewerkernummer (readonly) --}}
                        <div>
                            <label for="nummer" class="block text-sm font-medium text-gray-700">
                                Medewerkernummer
                            </label>
                            <input
                                type="text"
                                name="nummer"
                                id="nummer"
                                value="{{ $medewerker->nummer }}"
                                readonly
                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm"
                                aria-describedby="nummer-help"
                            >
                            <p class="mt-1 text-sm text-gray-500" id="nummer-help">Kan niet worden gewijzigd</p>
                        </div>

                        {{-- Medewerkertype --}}
                        <div>
                            <label for="medewerker_type" class="block text-sm font-medium text-gray-700">
                                Medewerkertype <span class="text-red-600">*</span>
                            </label>
                            <select
                                name="medewerker_type"
                                id="medewerker_type"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('medewerker_type') border-red-300 @enderror"
                                aria-describedby="medewerker-type-error"
                            >
                                <option value="">Selecteer een type</option>
                                @foreach($medewerkerTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('medewerker_type', $medewerker->medewerker_type) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('medewerker_type')
                                <p class="mt-2 text-sm text-red-600" id="medewerker-type-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Specialisatie --}}
                        <div class="sm:col-span-2">
                            <label for="specialisatie" class="block text-sm font-medium text-gray-700">
                                Specialisatie
                            </label>
                            <input
                                type="text"
                                name="specialisatie"
                                id="specialisatie"
                                value="{{ old('specialisatie', $medewerker->specialisatie) }}"
                                maxlength="255"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('specialisatie') border-red-300 @enderror"
                                aria-describedby="specialisatie-error"
                            >
                            @error('specialisatie')
                                <p class="mt-2 text-sm text-red-600" id="specialisatie-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Is Actief --}}
                        <div class="sm:col-span-2">
                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="is_actief"
                                    id="is_actief"
                                    value="1"
                                    {{ old('is_actief', $medewerker->is_actief) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <label for="is_actief" class="ml-2 block text-sm text-gray-900">
                                    Medewerker is actief
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Inactieve medewerkers kunnen niet worden verwijderd</p>
                        </div>

                        {{-- Opmerking --}}
                        <div class="sm:col-span-2">
                            <label for="opmerking" class="block text-sm font-medium text-gray-700">
                                Opmerking
                            </label>
                            <textarea
                                name="opmerking"
                                id="opmerking"
                                rows="3"
                                maxlength="1000"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('opmerking') border-red-300 @enderror"
                                aria-describedby="opmerking-error"
                            >{{ old('opmerking', $medewerker->opmerking) }}</textarea>
                            @error('opmerking')
                                <p class="mt-2 text-sm text-red-600" id="opmerking-error">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Maximaal 1000 karakters</p>
                        </div>
                    </div>
                </div>

                {{-- Actieknoppen --}}
                <div class="flex justify-end space-x-3">
                    <a
                        href="{{ route('medewerker.index') }}"
                        class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Annuleren
                    </a>
                    <button
                        type="submit"
                        id="submitBtn"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Medewerker Opslaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Client-side validatie script --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('medewerkerForm');
            const submitBtn = document.getElementById('submitBtn');

            // Form validatie bij submit
            form.addEventListener('submit', function(e) {
                let isValid = true;

                // Controleer verplichte velden
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-300');
                    } else {
                        field.classList.remove('border-red-300');
                    }
                });

                // Disable submit button om dubbele submits te voorkomen
                if (isValid) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Bezig met opslaan...';
                } else {
                    e.preventDefault();
                    alert('Vul alle verplichte velden correct in.');
                }
            });

            // Auto-hide error messages na 5 seconden
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
    @endpush
</x-layouts.app>
