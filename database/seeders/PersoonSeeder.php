<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Persoon;
use Illuminate\Database\Seeder;

class PersoonSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            'patient@gmail.com' => ['voornaam' => 'Peter', 'achternaam' => 'Patiënt', 'geboortedatum' => '1990-05-15'],
            'tandarts@gmail.com' => ['voornaam' => 'Tessa', 'achternaam' => 'Tandarts', 'geboortedatum' => '1985-03-20'],
            'mondhygienist@gmail.com' => ['voornaam' => 'Mila', 'achternaam' => 'Mondhygiënist', 'geboortedatum' => '1992-08-10'],
            'assistent@gmail.com' => ['voornaam' => 'Anna', 'achternaam' => 'Assistent', 'geboortedatum' => '1995-11-25'],
            'praktijkmanagement@gmail.com' => ['voornaam' => 'Paul', 'achternaam' => 'Management', 'geboortedatum' => '1980-02-14'],
            'jan.jansen@gmail.com' => ['voornaam' => 'Jan', 'achternaam' => 'Jansen', 'geboortedatum' => '1988-07-22'],
            'marie.smit@gmail.com' => ['voornaam' => 'Marie', 'achternaam' => 'Smit', 'geboortedatum' => '1993-12-05'],
        ];

        foreach ($users as $email => $data) {
            $user = User::where('email', $email)->first();
            if ($user) {
                Persoon::firstOrCreate(
                    ['gebruiker_id' => $user->id],
                    [
                        'voornaam' => $data['voornaam'],
                        'achternaam' => $data['achternaam'],
                        'geboortedatum' => $data['geboortedatum'],
                        'is_actief' => true,
                    ]
                );
            }
        }

        // Extra personen zonder gebruiker account
        Persoon::firstOrCreate(
            ['voornaam' => 'Emma', 'achternaam' => 'de Vries'],
            [
                'gebruiker_id' => null,
                'geboortedatum' => '1987-04-18',
                'is_actief' => true,
            ]
        );

        Persoon::firstOrCreate(
            ['voornaam' => 'Lucas', 'achternaam' => 'Bakker'],
            [
                'gebruiker_id' => null,
                'geboortedatum' => '1991-09-30',
                'is_actief' => true,
            ]
        );

        Persoon::firstOrCreate(
            ['voornaam' => 'Sophie', 'achternaam' => 'Visser'],
            [
                'gebruiker_id' => null,
                'geboortedatum' => '1989-06-12',
                'is_actief' => true,
            ]
        );
    }
}
