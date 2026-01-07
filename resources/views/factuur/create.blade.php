<x-layouts.app :title="__('Nieuwe Factuur')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <x-page-heading :title="__('Nieuwe Factuur Aanmaken')" />

        <div class="rounded-lg bg-white p-6 shadow">
            @if(session('error'))
                <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('factuur.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Factuurnummer -->
                <div>
                    <label for="nummer" class="block text-sm font-medium text-gray-700">
                        {{ __('Factuurnummer') }}
                    </label>
                    <input
                        type="text"
                        name="nummer"
                        id="nummer"
                        value="{{ $nextInvoiceNumber }}"
                        readonly
                        class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm"
                    >
                    <p class="mt-1 text-xs text-gray-500">{{ __('Wordt automatisch gegenereerd') }}</p>
                </div>

                <!-- Patiënt -->
                <div>
                    <label for="patient_id" class="block text-sm font-medium text-gray-700">
                        {{ __('Patiënt') }} <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="patient_id"
                        id="patient_id"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('patient_id') border-red-500 @enderror"
                    >
                        <option value="">{{ __('Selecteer een patiënt') }}</option>
                        @foreach($patienten as $patient)
                            <option value="{{ $patient['id'] }}" {{ old('patient_id') == $patient['id'] ? 'selected' : '' }}>
                                {{ $patient['naam'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Behandeling -->
                <div>
                    <label for="behandeling_id" class="block text-sm font-medium text-gray-700">
                        {{ __('Behandeling') }}
                    </label>
                    <select
                        name="behandeling_id"
                        id="behandeling_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('behandeling_id') border-red-500 @enderror"
                    >
                        <option value="">{{ __('Geen behandeling gekoppeld') }}</option>
                        @foreach($behandelingen as $behandeling)
                            <option
                                value="{{ $behandeling['id'] }}"
                                data-patient-id="{{ $behandeling['patient_id'] }}"
                                {{ old('behandeling_id') == $behandeling['id'] ? 'selected' : '' }}
                            >
                                {{ $behandeling['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('behandeling_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">{{ __('Optioneel: koppel de factuur aan een specifieke behandeling') }}</p>
                </div>

                <!-- Datum -->
                <div>
                    <label for="datum" class="block text-sm font-medium text-gray-700">
                        {{ __('Factuurdatum') }} <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        name="datum"
                        id="datum"
                        value="{{ old('datum', date('Y-m-d')) }}"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('datum') border-red-500 @enderror"
                    >
                    @error('datum')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bedrag -->
                <div>
                    <label for="bedrag" class="block text-sm font-medium text-gray-700">
                        {{ __('Bedrag (€)') }} <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="bedrag"
                        id="bedrag"
                        value="{{ old('bedrag') }}"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('bedrag') border-red-500 @enderror"
                        placeholder="0.00"
                    >
                    @error('bedrag')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">
                        {{ __('Status') }} <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="status"
                        id="status"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('status') border-red-500 @enderror"
                    >
                        <option value="Niet-Verzonden" {{ old('status') == 'Niet-Verzonden' ? 'selected' : '' }}>{{ __('Niet-Verzonden') }}</option>
                        <option value="Verzonden" {{ old('status') == 'Verzonden' ? 'selected' : '' }}>{{ __('Verzonden') }}</option>
                        <option value="Onbetaald" {{ old('status') == 'Onbetaald' ? 'selected' : '' }}>{{ __('Onbetaald') }}</option>
                        <option value="Betaald" {{ old('status') == 'Betaald' ? 'selected' : '' }}>{{ __('Betaald') }}</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Opmerking -->
                <div>
                    <label for="opmerking" class="block text-sm font-medium text-gray-700">
                        {{ __('Opmerking') }}
                    </label>
                    <textarea
                        name="opmerking"
                        id="opmerking"
                        rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('opmerking') border-red-500 @enderror"
                        placeholder="Eventuele opmerkingen..."
                    >{{ old('opmerking') }}</textarea>
                    @error('opmerking')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a
                        href="{{ route('factuur.index') }}"
                        class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        {{ __('Annuleren') }}
                    </a>
                    <button
                        type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        {{ __('Factuur Aanmaken') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Filter behandelingen based on selected patient
        document.getElementById('patient_id').addEventListener('change', function() {
            const patientId = this.value;
            const behandelingSelect = document.getElementById('behandeling_id');
            const options = behandelingSelect.querySelectorAll('option[data-patient-id]');

            options.forEach(option => {
                if (patientId === '' || option.dataset.patientId === patientId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });

            // Reset behandeling selection if current selection is now hidden
            if (behandelingSelect.value && behandelingSelect.selectedOptions[0].style.display === 'none') {
                behandelingSelect.value = '';
            }
        });
    </script>
</x-layouts.app>
