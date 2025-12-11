<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
    }
}
