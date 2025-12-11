<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $patienten = DB::table('patienten')->get()->keyBy('nummer');

        $contactData = [
            'P001' => [
                'straatnaam' => 'Hoofdstraat',
                'huisnummer' => '123',
                'postcode' => '1234AB',
                'plaats' => 'Amsterdam',
                'mobiel' => '0612345678',
                'email' => 'patient@gmail.com',
            ],
            'P002' => [
                'straatnaam' => 'Kerkstraat',
                'huisnummer' => '45',
                'postcode' => '2345BC',
                'plaats' => 'Rotterdam',
                'mobiel' => '0623456789',
                'email' => 'jan.jansen@gmail.com',
            ],
            'P003' => [
                'straatnaam' => 'Dorpsweg',
                'huisnummer' => '78',
                'toevoeging' => 'A',
                'postcode' => '3456CD',
                'plaats' => 'Utrecht',
                'mobiel' => '0634567890',
                'email' => 'marie.smit@gmail.com',
            ],
            'P004' => [
                'straatnaam' => 'Laan van Meerdervoort',
                'huisnummer' => '234',
                'postcode' => '4567DE',
                'plaats' => 'Den Haag',
                'mobiel' => '0645678901',
                'email' => 'emma.devries@gmail.com',
            ],
            'P005' => [
                'straatnaam' => 'Marktplein',
                'huisnummer' => '12',
                'postcode' => '5678EF',
                'plaats' => 'Eindhoven',
                'mobiel' => '0656789012',
                'email' => 'lucas.bakker@gmail.com',
            ],
        ];

        foreach ($contactData as $nummer => $data) {
            if (isset($patienten[$nummer])) {
                DB::table('contacts')->updateOrInsert(
                    ['patient_id' => $patienten[$nummer]->id],
                    array_merge($data, [
                        'patient_id' => $patienten[$nummer]->id,
                        'is_actief' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])
                );
            }
        }
    }
}
