<x-layouts.app :title="__('Beschikbaarheid toevoegen')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <x-page-heading :title="__('Beschikbaarheid toevoegen')" />

        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <form action="{{ route('beschikbaarheid.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="user_id" class="block text-sm font-medium text-zinc-900 dark:text-zinc-100">
                        {{ __('Medewerker') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="user_id" name="user_id" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 sm:text-sm">
                        @foreach($medewerkers as $medewerker)
                            <option value="{{ $medewerker->id }}" {{ old('user_id', auth()->id()) == $medewerker->id ? 'selected' : '' }}>
                                {{ $medewerker->gebruikersnaam }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="datum_vanaf" class="block text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            {{ __('Van datum') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="datum_vanaf" name="datum_vanaf" value="{{ old('datum_vanaf') }}" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 sm:text-sm">
                        @error('datum_vanaf')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="datum_tot_met" class="block text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            {{ __('Tot datum') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="datum_tot_met" name="datum_tot_met" value="{{ old('datum_tot_met') }}" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 sm:text-sm">
                        @error('datum_tot_met')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="tijd_vanaf" class="block text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            {{ __('Van tijd') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="tijd_vanaf" name="tijd_vanaf" value="{{ old('tijd_vanaf', '09:00') }}" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 sm:text-sm">
                        @error('tijd_vanaf')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tijd_tot_met" class="block text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            {{ __('Tot tijd') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="tijd_tot_met" name="tijd_tot_met" value="{{ old('tijd_tot_met', '17:00') }}" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 sm:text-sm">
                        @error('tijd_tot_met')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-zinc-900 dark:text-zinc-100">
                        {{ __('Status') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 sm:text-sm">
                        <option value="Aanwezig" {{ old('status', 'Aanwezig') == 'Aanwezig' ? 'selected' : '' }}>{{ __('Aanwezig') }}</option>
                        <option value="Afwezig" {{ old('status') == 'Afwezig' ? 'selected' : '' }}>{{ __('Afwezig') }}</option>
                        <option value="Verlof" {{ old('status') == 'Verlof' ? 'selected' : '' }}>{{ __('Verlof') }}</option>
                        <option value="Ziek" {{ old('status') == 'Ziek' ? 'selected' : '' }}>{{ __('Ziek') }}</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="opmerking" class="block text-sm font-medium text-zinc-900 dark:text-zinc-100">
                        {{ __('Opmerking') }}
                    </label>
                    <textarea id="opmerking" name="opmerking" rows="3" class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 sm:text-sm">{{ old('opmerking') }}</textarea>
                    @error('opmerking')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center rounded-md bg-lime-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-lime-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-lime-600">
                        {{ __('Opslaan') }}
                    </button>
                    <a href="{{ route('beschikbaarheid.index') }}" class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                        {{ __('Annuleren') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
