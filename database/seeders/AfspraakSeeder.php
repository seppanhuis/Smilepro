<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Afspraak;

class AfspraakSeeder extends Seeder
{
    public function run(): void
    {
        Afspraak::create([
            'patient_id' => 1,
            'datum' => '2025-01-20',
            'starttijd' => '10:00',
            'eindtijd' => '10:30',
        ]);

        Afspraak::create([
            'patient_id' => 1,
            'datum' => '2025-01-22',
            'starttijd' => '14:00',
            'eindtijd' => '14:30',
        ]);

        Afspraak::create([
            'patient_id' => 1,
            'datum' => '2025-01-25',
            'starttijd' => '09:00',
            'eindtijd' => '09:45',
        ]);
    }
}
