<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PersoonSeeder::class,
            PatientenSeeder::class,
            MedewerkerSeeder::class,
            BeschikbaarheidSeeder::class,
            ContactSeeder::class,
            AfspraakSeeder::class,
            BehandelingSeeder::class,
            FactuurSeeder::class,
        ]);

        // Nep conversatie tussen 'Patiënt' en 'Tandarts'
        $patient = \App\Models\User::where('gebruikersnaam', 'Patiënt')->first();
        $tandarts = \App\Models\User::where('gebruikersnaam', 'Tandarts')->first();
        if ($patient && $tandarts) {
            DB::table('communicaties')->insert([
                [
                    'afzender_id' => $patient->id,
                    'ontvanger_id' => $tandarts->id,
                    'bericht' => 'Hallo dokter, ik heb kiespijn.',
                    'datum' => now()->subDays(1),
                    'is_actief' => 1,
                    'opmerking' => null,
                    'created_at' => now()->subDays(1),
                    'updated_at' => now()->subDays(1),
                ],
                [
                    'afzender_id' => $tandarts->id,
                    'ontvanger_id' => $patient->id,
                    'bericht' => 'Beste Patiënt, maak gerust een afspraak.',
                    'datum' => now()->subHours(20),
                    'is_actief' => 1,
                    'opmerking' => null,
                    'created_at' => now()->subHours(20),
                    'updated_at' => now()->subHours(20),
                ],
            ]);
        }
    }
}
