<?php

namespace Database\Seeders;

use App\Models\Medewerker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BehandelingSeeder extends Seeder
{
    public function run(): void
    {
        $patienten = DB::table('patienten')->get()->keyBy('nummer');
        $medewerker1 = Medewerker::where('nummer', 'M001')->first();
        $medewerker2 = Medewerker::where('nummer', 'M002')->first();
        $medewerker5 = Medewerker::where('nummer', 'M005')->first();

        $behandelingData = [
            [
                'medewerker_id' => $medewerker1?->id,
                'patient_id' => $patienten['P001']->id ?? null,
                'datum' => '2025-11-15',
                'tijd' => '10:00:00',
                'behandeling_type' => 'Controles',
                'omschrijving' => 'Algemene controle uitgevoerd, geen problemen gevonden',
                'kosten' => 45.00,
                'status' => 'Behandeld',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker2?->id,
                'patient_id' => $patienten['P002']->id ?? null,
                'datum' => '2025-11-20',
                'tijd' => '14:00:00',
                'behandeling_type' => 'Gebitsreiniging',
                'omschrijving' => 'Grondige gebitsreiniging en polijsten',
                'kosten' => 65.00,
                'status' => 'Behandeld',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker1?->id,
                'patient_id' => $patienten['P003']->id ?? null,
                'datum' => '2025-11-22',
                'tijd' => '11:00:00',
                'behandeling_type' => 'Vullingen',
                'omschrijving' => 'Vulling in kies 36 geplaatst',
                'kosten' => 125.00,
                'status' => 'Behandeld',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker5?->id,
                'patient_id' => $patienten['P004']->id ?? null,
                'datum' => '2025-12-18',
                'tijd' => '15:00:00',
                'behandeling_type' => 'Orthodontie',
                'omschrijving' => 'Orthodontie intake en planning besproken',
                'kosten' => 85.00,
                'status' => 'Onbehandeld',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker1?->id,
                'patient_id' => $patienten['P005']->id ?? null,
                'datum' => '2025-10-10',
                'tijd' => '09:00:00',
                'behandeling_type' => 'Wortelkanaalbehandelingen',
                'omschrijving' => 'Wortelkanaalbehandeling kies 46',
                'kosten' => 350.00,
                'status' => 'Behandeld',
                'is_actief' => true,
            ],
        ];

        foreach ($behandelingData as $data) {
            if ($data['medewerker_id'] && $data['patient_id']) {
                DB::table('behandelingen')->insert(
                    array_merge($data, ['created_at' => now(), 'updated_at' => now()])
                );
            }
        }
    }
}
