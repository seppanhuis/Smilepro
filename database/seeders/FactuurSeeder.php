<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FactuurSeeder extends Seeder
{
    public function run(): void
    {
        $patienten = DB::table('patienten')->get()->keyBy('nummer');
        $behandelingIds = DB::table('behandelingen')->pluck('id')->toArray();

        $factuurData = [
            [
                'nummer' => 'F2025001',
                'patient_id' => $patienten['P001']->id ?? null,
                'behandeling_id' => $behandelingIds[0] ?? null,
                'bedrag' => 45.00,
                'status' => 'Verzonden',
                'datum' => '2025-11-16',
            ],
            [
                'nummer' => 'F2025002',
                'patient_id' => $patienten['P002']->id ?? null,
                'behandeling_id' => $behandelingIds[1] ?? null,
                'bedrag' => 65.00,
                'status' => 'Verzonden',
                'datum' => '2025-11-21',
            ],
            [
                'nummer' => 'F2025003',
                'patient_id' => $patienten['P003']->id ?? null,
                'behandeling_id' => $behandelingIds[2] ?? null,
                'bedrag' => 125.00,
                'status' => 'Verzonden',
                'datum' => '2025-11-23',
            ],
            [
                'nummer' => 'F2025004',
                'patient_id' => $patienten['P004']->id ?? null,
                'behandeling_id' => $behandelingIds[3] ?? null,
                'bedrag' => 85.00,
                'status' => 'Verzonden',
                'datum' => '2025-12-19',
            ],
            [
                'nummer' => 'F2025005',
                'patient_id' => $patienten['P005']->id ?? null,
                'behandeling_id' => $behandelingIds[4] ?? null,
                'bedrag' => 350.00,
                'status' => 'Verzonden',
                'datum' => '2025-10-11',
            ],
        ];

        foreach ($factuurData as $data) {
            if ($data['patient_id']) {
                DB::table('facturen')->updateOrInsert(
                    ['nummer' => $data['nummer']],
                    array_merge($data, ['created_at' => now(), 'updated_at' => now()])
                );
            }
        }
    }
}
