<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunicatieSeeder extends Seeder
{
    public function run(): void
    {
        // Haal patienten en medewerkers
        $patienten = DB::table('users')->where('rol_naam', 'patient')->get()->keyBy('id');
        $medewerkers = DB::table('users')->whereIn('rol_naam', ['tandarts','mondhygiënist'])->get()->keyBy('id');

        $communicatieData = [
            [
                'afzender_id' => $patienten->first()->id,
                'ontvanger_id' => $medewerkers->first()->id,
                'bericht' => 'Goedemiddag, ik heb een vraag over mijn afspraak.',
                'datum' => now()->subDays(5),
                'is_actief' => 1,
                'opmerking' => null,
            ],
            [
                'afzender_id' => $medewerkers->first()->id,
                'ontvanger_id' => $patienten->first()->id,
                'bericht' => 'Hallo, uw afspraak is bevestigd.',
                'datum' => now()->subDays(4),
                'is_actief' => 1,
                'opmerking' => null,
            ],
            [
                'afzender_id' => $patienten->skip(1)->first()->id ?? $patienten->first()->id,
                'ontvanger_id' => $medewerkers->skip(1)->first()->id ?? $medewerkers->first()->id,
                'bericht' => 'Kan ik mijn afspraak verzetten?',
                'datum' => now()->subDays(3),
                'is_actief' => 1,
                'opmerking' => null,
            ],
        ];

        foreach ($communicatieData as $data) {
            DB::table('communicaties')->insert(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}
