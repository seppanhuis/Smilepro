<div class="px-6 py-10 max-w-5xl mx-auto">

    <h1 class="text-3xl font-bold text-gray-900 mb-1">Overzicht afspraken</h1>
    <p class="text-gray-600 mb-8">Hier vind je al jouw ingeplande afspraken.</p>

    @if ($afspraken->isEmpty())
        <div class="p-6 bg-yellow-100 text-yellow-800 rounded-lg border border-yellow-300">
            Er zijn nog geen afspraken ingepland.
        </div>
    @else

        <div class="overflow-hidden rounded-xl shadow-xl border border-gray-200 bg-white">
            <table class="w-full border-collapse text-sm">
                <thead class="bg-gray-100 text-left text-gray-700 uppercase tracking-wider text-xs border-b">
                    <tr>
                        <th class="px-6 py-3">Datum</th>
                        <th class="px-6 py-3">Tijd</th>
                        <th class="px-6 py-3">Medewerker</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Opmerking</th>
                    </tr>
                </thead>

                <tbody class="text-gray-800">
                    @foreach ($afspraken as $afspraak)
                        <tr class="border-b hover:bg-gray-50 transition">

                            {{-- Datum --}}
                            <td class="px-6 py-4 font-medium">
                                {{ \Carbon\Carbon::parse($afspraak->datum)->translatedFormat('d F Y') }}
                            </td>

                            {{-- Tijd --}}
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($afspraak->tijd)->format('H:i') }}
                            </td>

                            {{-- Medewerker naam --}}
                            <td class="px-6 py-4">
                                @php
                                    $medewerker = $afspraak->medewerker()->with('persoon')->first();
                                @endphp

                                @if($medewerker && $medewerker->persoon)
                                    <span class="font-medium text-gray-900">
                                        {{ $medewerker->medewerker_type }} {{ $medewerker->persoon->voornaam }}
                                    </span>
                                    <span class="text-gray-500 text-xs">
                                        ({{ $medewerker->specialisatie ?? 'Geen specialisatie' }})
                                    </span>
                                @else
                                    —
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                @if ($afspraak->status === 'Bevestigd')
                                    <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                        Bevestigd
                                    </span>
                                @elseif ($afspraak->status === 'Geannuleerd')
                                    <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                        Geannuleerd
                                    </span>
                                @elseif ($afspraak->status === 'In afwachting')
                                    <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                        In afwachting
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded-full">
                                        {{ $afspraak->status }}
                                    </span>
                                @endif
                            </td>

                            {{-- Opmerking --}}
                            <td class="px-6 py-4 text-gray-600">
                                {{ $afspraak->opmerking ?? '—' }}
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @endif
</div>
