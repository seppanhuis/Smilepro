<?php

namespace Database\Seeders;

use App\Models\Persoon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientenSeeder extends Seeder
{
    public function run(): void
    {
        $personen = [
            'Peter' => ['nummer' => 'P001', 'medisch_dossier' => 'Geen bijzonderheden'],
            'Jan' => ['nummer' => 'P002', 'medisch_dossier' => 'Allergie voor penicilline'],
            'Marie' => ['nummer' => 'P003', 'medisch_dossier' => 'Diabetes type 2'],
            'Emma' => ['nummer' => 'P004', 'medisch_dossier' => 'Hoge bloeddruk'],
            'Lucas' => ['nummer' => 'P005', 'medisch_dossier' => 'Geen bijzonderheden'],
        ];

        foreach ($personen as $voornaam => $data) {
            $persoon = Persoon::where('voornaam', $voornaam)->first();
            if ($persoon) {
                DB::table('patienten')->updateOrInsert(
                    ['nummer' => $data['nummer']],
                    [
                        'persoon_id' => $persoon->id,
                        'nummer' => $data['nummer'],
                        'medisch_dossier' => $data['medisch_dossier'],
                        'is_actief' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
