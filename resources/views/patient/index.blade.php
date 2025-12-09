@vite(['resources/css/app.css', 'resources/js/app.js'])

<x-layouts.app class="dark:bg-zinc-800" :title="__('Patiënten Overzicht')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="dark:bg-zinc-800 relative overflow-hidden rounded-xl p-6">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Overzicht Patiënten</h1>

            @if(count($patients) > 0)
                <div class=" overflow-x-auto">
                    <table class="min-w-full light:bg-white light:text-white border border-gray-200 dark:border-neutral-700 text-gray-900 dark:text-gray-100">
                        <thead class=" light:bg-neutral-900">
                            <tr>
                                <th class="px-4 py-2 border">Gebruikersnaam</th>
                                <th class="px-4 py-2 border">Email</th>
                                <th class="px-4 py-2 border">Rol</th>
                                <th class="px-4 py-2 border">Actief</th>
                                <th class="px-4 py-2 border">Opmerking</th>
                            </tr>
                        </thead>
                        <tbody class="light:bg-neutral-900">
                            @foreach($patients as $patient)
                                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800">
                                    <td class="px-4 py-2 border">{{ $patient->gebruikersnaam }}</td>
                                    <td class="px-4 py-2 border">{{ $patient->email }}</td>
                                    <td class="px-4 py-2 border">{{ $patient->rol_naam }}</td>
                                    <td class="px-4 py-2 border">
                                        @if($patient->is_actief)
                                            <span class="text-green-600 font-semibold">Ja</span>
                                        @else
                                            <span class="text-red-600 font-semibold">Nee</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border">{{ $patient->opmerking ?? 'nee' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">Geen patiënten gevonden.</p>
            @endif
        </div>
    </div>
</x-layouts.app>
