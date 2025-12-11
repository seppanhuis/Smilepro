<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account met management rechten
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'gebruikersnaam' => 'Admin',
                'password' => 'Admin123',
                'rol_naam' => 'praktijkmanagement',
                'is_actief' => true,
                'email_verified_at' => now(),
            ]
        );

        // Patiënt account
        User::firstOrCreate(
            ['email' => 'patient@gmail.com'],
            [
                'gebruikersnaam' => 'Patiënt',
                'password' => 'Admin123',
                'rol_naam' => 'patient',
                'is_actief' => true,
                'email_verified_at' => now(),
            ]
        );

        // Tandarts account
        User::firstOrCreate(
            ['email' => 'tandarts@gmail.com'],
            [
                'gebruikersnaam' => 'Tandarts',
                'password' => 'Admin123',
                'rol_naam' => 'tandarts',
                'is_actief' => true,
                'email_verified_at' => now(),
            ]
        );

        // Mondhygiënist account
        User::firstOrCreate(
            ['email' => 'mondhygienist@gmail.com'],
            [
                'gebruikersnaam' => 'Mondhygiënist',
                'password' => 'Admin123',
                'rol_naam' => 'mondhygiënist',
                'is_actief' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assistent account
        User::firstOrCreate(
            ['email' => 'assistent@gmail.com'],
            [
                'gebruikersnaam' => 'Assistent',
                'password' => 'Admin123',
                'rol_naam' => 'assistent',
                'is_actief' => true,
                'email_verified_at' => now(),
            ]
        );

        // Praktijkmanagement account
        User::firstOrCreate(
            ['email' => 'praktijkmanagement@gmail.com'],
            [
                'gebruikersnaam' => 'Praktijkmanagement',
                'password' => 'Admin123',
                'rol_naam' => 'praktijkmanagement',
                'is_actief' => true,
                'email_verified_at' => now(),
            ]
        );

        // Extra user accounts
        User::firstOrCreate(
            ['email' => 'jan.jansen@gmail.com'],
            [
                'gebruikersnaam' => 'JanJansen',
                'password' => 'Admin123',
                'rol_naam' => 'patient',
                'is_actief' => true,
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'marie.smit@gmail.com'],
            [
                'gebruikersnaam' => 'MarieSmit',
                'password' => 'Admin123',
                'rol_naam' => 'patient',
                'is_actief' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
