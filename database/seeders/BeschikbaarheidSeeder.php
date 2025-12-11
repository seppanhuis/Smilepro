<?php

namespace Database\Seeders;

use App\Models\Medewerker;
use App\Models\Beschikbaarheid;
use Illuminate\Database\Seeder;

class BeschikbaarheidSeeder extends Seeder
{
    public function run(): void
    {
        $medewerker1 = Medewerker::where('nummer', 'M001')->first();
        $medewerker2 = Medewerker::where('nummer', 'M002')->first();
        $medewerker3 = Medewerker::where('nummer', 'M003')->first();
        $medewerker5 = Medewerker::where('nummer', 'M005')->first();

        $beschikbaarheidData = [
            [
                'medewerker_id' => $medewerker1?->id,
                'datum_vanaf' => '2025-12-01',
                'datum_tot_met' => '2025-12-31',
                'tijd_vanaf' => '09:00:00',
                'tijd_tot_met' => '17:00:00',
                'status' => 'Aanwezig',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker2?->id,
                'datum_vanaf' => '2025-12-01',
                'datum_tot_met' => '2025-12-31',
                'tijd_vanaf' => '08:00:00',
                'tijd_tot_met' => '16:00:00',
                'status' => 'Aanwezig',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker3?->id,
                'datum_vanaf' => '2025-12-01',
                'datum_tot_met' => '2025-12-31',
                'tijd_vanaf' => '09:00:00',
                'tijd_tot_met' => '17:00:00',
                'status' => 'Aanwezig',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker1?->id,
                'datum_vanaf' => '2025-12-23',
                'datum_tot_met' => '2025-12-27',
                'tijd_vanaf' => '00:00:00',
                'tijd_tot_met' => '23:59:59',
                'status' => 'Verlof',
                'is_actief' => true,
                'opmerking' => 'Kerstvakantie',
            ],
            [
                'medewerker_id' => $medewerker5?->id,
                'datum_vanaf' => '2025-12-01',
                'datum_tot_met' => '2025-12-31',
                'tijd_vanaf' => '10:00:00',
                'tijd_tot_met' => '18:00:00',
                'status' => 'Aanwezig',
                'is_actief' => true,
            ],
        ];

        foreach ($beschikbaarheidData as $data) {
            if ($data['medewerker_id']) {
                Beschikbaarheid::firstOrCreate(
                    [
                        'medewerker_id' => $data['medewerker_id'],
                        'datum_vanaf' => $data['datum_vanaf'],
                        'datum_tot_met' => $data['datum_tot_met'],
                    ],
                    $data
                );
            }
        }
    }
}
