

<x-layouts.app :title="__('Nieuwe Medewerker Toevoegen')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <x-page-heading :title="__('Nieuwe Medewerker Toevoegen')" />

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

        {{-- Succesmeldingen --}}
        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Formulier --}}
        <div class="bg-white shadow-sm rounded-lg">
            <form
                action="{{ route('medewerker.store') }}"
                method="POST"
                id="medewerkerForm"
                class="space-y-6 p-6"
                novalidate
            >
                @csrf

                {{-- Gebruikersgegevens Sectie --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Gebruikersgegevens</h3>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        {{-- Gebruikersnaam --}}
                        <div>
                            <label for="gebruikersnaam" class="block text-sm font-medium text-gray-700">
                                Gebruikersnaam <span class="text-red-600">*</span>
                            </label>
                            <input
                                type="text"
                                name="gebruikersnaam"
                                id="gebruikersnaam"
                                value="{{ old('gebruikersnaam') }}"
                                required
                                minlength="3"
                                maxlength="255"
                                pattern="[a-zA-Z0-9\s]+"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('gebruikersnaam') border-red-300 @enderror"
                                aria-describedby="gebruikersnaam-error"
                            >
                            @error('gebruikersnaam')
                                <p class="mt-2 text-sm text-red-600" id="gebruikersnaam-error">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Minimaal 3 karakters, alleen letters, cijfers en spaties</p>
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
                                value="{{ old('email') }}"
                                required
                                maxlength="255"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('email') border-red-300 @enderror"
                                aria-describedby="email-error"
                            >
                            @error('email')
                                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Wachtwoord --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Wachtwoord <span class="text-red-600">*</span>
                            </label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                required
                                minlength="8"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('password') border-red-300 @enderror"
                                aria-describedby="password-error password-help"
                            >
                            @error('password')
                                <p class="mt-2 text-sm text-red-600" id="password-error">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500" id="password-help">
                                Minimaal 8 karakters, hoofdletters, kleine letters, cijfers en symbolen
                            </p>
                        </div>

                        {{-- Wachtwoord Bevestiging --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Bevestig Wachtwoord <span class="text-red-600">*</span>
                            </label>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                required
                                minlength="8"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                aria-describedby="password-confirmation-error"
                            >
                            <p class="mt-2 text-sm text-red-600 hidden" id="password-confirmation-error"></p>
                        </div>

                        {{-- Rol --}}
                        <div>
                            <label for="rol_naam" class="block text-sm font-medium text-gray-700">
                                Rol <span class="text-red-600">*</span>
                            </label>
                            <select
                                name="rol_naam"
                                id="rol_naam"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('rol_naam') border-red-300 @enderror"
                                aria-describedby="rol-error"
                            >
                                <option value="">Selecteer een rol</option>
                                @foreach($rollen as $key => $label)
                                    <option value="{{ $key }}" {{ old('rol_naam') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rol_naam')
                                <p class="mt-2 text-sm text-red-600" id="rol-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

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
                                value="{{ old('voornaam') }}"
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
                                value="{{ old('tussenvoegsel') }}"
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
                                value="{{ old('achternaam') }}"
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

                        {{-- Geboortedatum --}}
                        <div>
                            <label for="geboortedatum" class="block text-sm font-medium text-gray-700">
                                Geboortedatum <span class="text-red-600">*</span>
                            </label>
                            <input
                                type="date"
                                name="geboortedatum"
                                id="geboortedatum"
                                value="{{ old('geboortedatum') }}"
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
                        {{-- Medewerkernummer --}}
                        <div>
                            <label for="nummer" class="block text-sm font-medium text-gray-700">
                                Medewerkernummer <span class="text-red-600">*</span>
                            </label>
                            <input
                                type="text"
                                name="nummer"
                                id="nummer"
                                value="{{ old('nummer', $gegenereerdeNummer) }}"
                                required
                                maxlength="50"
                                pattern="[A-Z0-9-]+"
                                readonly
                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('nummer') border-red-300 @enderror"
                                aria-describedby="nummer-error nummer-help"
                            >
                            @error('nummer')
                                <p class="mt-2 text-sm text-red-600" id="nummer-error">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500" id="nummer-help">Automatisch gegenereerd</p>
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
                                    <option value="{{ $key }}" {{ old('medewerker_type') == $key ? 'selected' : '' }}>
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
                                value="{{ old('specialisatie') }}"
                                maxlength="255"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('specialisatie') border-red-300 @enderror"
                                aria-describedby="specialisatie-error"
                            >
                            @error('specialisatie')
                                <p class="mt-2 text-sm text-red-600" id="specialisatie-error">{{ $message }}</p>
                            @enderror
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
                            >{{ old('opmerking') }}</textarea>
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
                        Medewerker Toevoegen
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
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirmation');
            const nummerInput = document.getElementById('nummer');

            // Real-time wachtwoord validatie
            passwordConfirmInput.addEventListener('input', function() {
                const errorElement = document.getElementById('password-confirmation-error');
                if (passwordInput.value !== passwordConfirmInput.value) {
                    errorElement.textContent = 'De wachtwoorden komen niet overeen.';
                    errorElement.classList.remove('hidden');
                    passwordConfirmInput.classList.add('border-red-300');
                } else {
                    errorElement.textContent = '';
                    errorElement.classList.add('hidden');
                    passwordConfirmInput.classList.remove('border-red-300');
                }
            });

            // Automatisch hoofdletters voor medewerkernummer
            nummerInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

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

                // Controleer wachtwoord match
                if (passwordInput.value !== passwordConfirmInput.value) {
                    isValid = false;
                    e.preventDefault();
                    alert('De wachtwoorden komen niet overeen.');
                    return;
                }

                // Disable submit button om dubbele submits te voorkomen
                if (isValid) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Bezig met toevoegen...';
                } else {
                    e.preventDefault();
                    alert('Vul alle verplichte velden correct in.');
                }
            });

            // Auto-hide success/error messages na 5 seconden
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
