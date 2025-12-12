<?php

namespace Database\Seeders;

use App\Models\Medewerker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AfspraakSeeder extends Seeder
{
    public function run(): void
    {
        $patienten = DB::table('patienten')->get()->keyBy('nummer');
        $medewerker1 = Medewerker::where('nummer', 'M001')->first();
        $medewerker2 = Medewerker::where('nummer', 'M002')->first();
        $medewerker5 = Medewerker::where('nummer', 'M005')->first();

        $afspraakData = [
            [
                'patient_id' => $patienten['P001']->id ?? null,
                'medewerker_id' => $medewerker1?->id,
                'datum' => '2025-12-15',
                'tijd' => '10:00:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Controle afspraak',
            ],
             [
                'patient_id' => $patienten['P001']->id ?? null,
                'medewerker_id' => $medewerker2?->id,
                'datum' => '2025-12-16',
                'tijd' => '14:00:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Gebitsreiniging',
            ],
             [
                'patient_id' => $patienten['P001']->id ?? null,
                'medewerker_id' => $medewerker2?->id,
                'datum' => '2025-12-06',
                'tijd' => '14:00:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Gebitsreiniging',
            ],
            [
                'patient_id' => $patienten['P001']->id ?? null,
                'medewerker_id' => $medewerker1?->id,
                'datum' => '2025-12-15',
                'tijd' => '10:00:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Controle afspraak',
            ],
             [
                'patient_id' => $patienten['P001']->id ?? null,
                'medewerker_id' => $medewerker2?->id,
                'datum' => '2025-12-16',
                'tijd' => '14:00:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Gebitsreiniging',
            ],
             [
                'patient_id' => $patienten['P001']->id ?? null,
                'medewerker_id' => $medewerker2?->id,
                'datum' => '2025-12-06',
                'tijd' => '14:00:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Gebitsreiniging',
            ],
            [
                'patient_id' => $patienten['P002']->id ?? null,
                'medewerker_id' => $medewerker2?->id,
                'datum' => '2025-12-16',
                'tijd' => '14:00:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Gebitsreiniging',
            ],
            [
                'patient_id' => $patienten['P003']->id ?? null,
                'medewerker_id' => $medewerker1?->id,
                'datum' => '2025-12-17',
                'tijd' => '11:30:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Vulling vervangen',
            ],
            [
                'patient_id' => $patienten['P004']->id ?? null,
                'medewerker_id' => $medewerker5?->id,
                'datum' => '2025-12-18',
                'tijd' => '15:00:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Orthodontie consult',
            ],
            [
                'patient_id' => $patienten['P005']->id ?? null,
                'medewerker_id' => $medewerker2?->id,
                'datum' => '2025-12-19',
                'tijd' => '09:30:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Periodieke controle',
            ],
        ];

        foreach ($afspraakData as $data) {
            if ($data['patient_id'] && $data['medewerker_id']) {
                DB::table('afspraken')->insert(
                    array_merge($data, ['created_at' => now(), 'updated_at' => now()])
                );
            }
        }
    }
}
