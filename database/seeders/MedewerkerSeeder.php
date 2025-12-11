<?php

namespace Database\Seeders;

use App\Models\Persoon;
use App\Models\Medewerker;
use Illuminate\Database\Seeder;

class MedewerkerSeeder extends Seeder
{
    public function run(): void
    {
        $medewerkers = [
            'Tessa' => ['nummer' => 'M001', 'type' => 'Tandarts', 'specialisatie' => 'Algemene tandheelkunde'],
            'Mila' => ['nummer' => 'M002', 'type' => 'Mondhygiënist', 'specialisatie' => 'Preventieve zorg'],
            'Anna' => ['nummer' => 'M003', 'type' => 'Assistent', 'specialisatie' => null],
            'Paul' => ['nummer' => 'M004', 'type' => 'Praktijkmanagement', 'specialisatie' => 'Administratie'],
            'Sophie' => ['nummer' => 'M005', 'type' => 'Tandarts', 'specialisatie' => 'Orthodontie'],
        ];

        foreach ($medewerkers as $voornaam => $data) {
            $persoon = Persoon::where('voornaam', $voornaam)->first();
            if ($persoon) {
                Medewerker::firstOrCreate(
                    ['nummer' => $data['nummer']],
                    [
                        'persoon_id' => $persoon->id,
                        'medewerker_type' => $data['type'],
                        'specialisatie' => $data['specialisatie'],
                        'is_actief' => true,
                    ]
                );
            }
        }
    }
}
