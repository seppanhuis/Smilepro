<?php
 
namespace Database\Seeders;
 
use App\Models\User;
use App\Models\Persoon;
use App\Models\Medewerker;
use App\Models\Beschikbaarheid;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
 
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==================== USERS ====================
        // Admin account met management rechten
        $admin = User::firstOrCreate(
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
        $patient1 = User::firstOrCreate(
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
        $tandarts = User::firstOrCreate(
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
        $mondhygienist = User::firstOrCreate(
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
        $assistent = User::firstOrCreate(
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
        $management = User::firstOrCreate(
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
        $patient2 = User::firstOrCreate(
            ['email' => 'jan.jansen@gmail.com'],
            [
                'gebruikersnaam' => 'JanJansen',
                'password' => 'Admin123',
                'rol_naam' => 'patient',
                'is_actief' => true,
                'email_verified_at' => now(),
            ]
        );

 
        $patient3 = User::firstOrCreate(
            ['email' => 'marie.smit@gmail.com'],
            [
                'gebruikersnaam' => 'MarieSmit',
                'password' => 'Admin123',
                'rol_naam' => 'patient',
                'is_actief' => true,
                'email_verified_at' => now(),
            ]
        );

 
        // ==================== PERSONEN ====================
        $persoon1 = Persoon::firstOrCreate(
            ['gebruiker_id' => $patient1->id],
            [
                'voornaam' => 'Peter',
                'achternaam' => 'Patiënt',
                'geboortedatum' => '1990-05-15',
                'is_actief' => true,
            ]
        );

 
        $persoon2 = Persoon::firstOrCreate(
            ['gebruiker_id' => $tandarts->id],
            [
                'voornaam' => 'Tessa',
                'achternaam' => 'Tandarts',
                'geboortedatum' => '1985-03-20',
                'is_actief' => true,
            ]
        );

 
        $persoon3 = Persoon::firstOrCreate(
            ['gebruiker_id' => $mondhygienist->id],
            [
                'voornaam' => 'Mila',
                'achternaam' => 'Mondhygiënist',
                'geboortedatum' => '1992-08-10',
                'is_actief' => true,
            ]
        );

 
        $persoon4 = Persoon::firstOrCreate(
            ['gebruiker_id' => $assistent->id],
            [
                'voornaam' => 'Anna',
                'achternaam' => 'Assistent',
                'geboortedatum' => '1995-11-25',
                'is_actief' => true,
            ]
        );

 
        $persoon5 = Persoon::firstOrCreate(
            ['gebruiker_id' => $management->id],
            [
                'voornaam' => 'Paul',
                'achternaam' => 'Management',
                'geboortedatum' => '1980-02-14',
                'is_actief' => true,
            ]
        );

 
        $persoon6 = Persoon::firstOrCreate(
            ['gebruiker_id' => $patient2->id],
            [
                'voornaam' => 'Jan',
                'achternaam' => 'Jansen',
                'geboortedatum' => '1988-07-22',
                'is_actief' => true,
            ]
        );

 
        $persoon7 = Persoon::firstOrCreate(
            ['gebruiker_id' => $patient3->id],
            [
                'voornaam' => 'Marie',
                'achternaam' => 'Smit',
                'geboortedatum' => '1993-12-05',
                'is_actief' => true,
            ]
        );

 
        // Extra personen zonder gebruiker account
        $persoon8 = Persoon::firstOrCreate(
            ['voornaam' => 'Emma', 'achternaam' => 'de Vries'],
            [
                'gebruiker_id' => null,
                'geboortedatum' => '1987-04-18',
                'is_actief' => true,
            ]
        );

 
        $persoon9 = Persoon::firstOrCreate(
            ['voornaam' => 'Lucas', 'achternaam' => 'Bakker'],
            [
                'gebruiker_id' => null,
                'geboortedatum' => '1991-09-30',
                'is_actief' => true,
            ]
        );

 
        // ==================== PATIENTEN ====================
        $patientData = [
            ['persoon_id' => $persoon1->id, 'nummer' => 'P001', 'medisch_dossier' => 'Geen bijzonderheden', 'is_actief' => true],
            ['persoon_id' => $persoon6->id, 'nummer' => 'P002', 'medisch_dossier' => 'Allergie voor penicilline', 'is_actief' => true],
            ['persoon_id' => $persoon7->id, 'nummer' => 'P003', 'medisch_dossier' => 'Diabetes type 2', 'is_actief' => true],
            ['persoon_id' => $persoon8->id, 'nummer' => 'P004', 'medisch_dossier' => 'Hoge bloeddruk', 'is_actief' => true],
            ['persoon_id' => $persoon9->id, 'nummer' => 'P005', 'medisch_dossier' => 'Geen bijzonderheden', 'is_actief' => true],
        ];

 
        foreach ($patientData as $data) {
            DB::table('patienten')->updateOrInsert(
                ['nummer' => $data['nummer']],
                array_merge($data, ['created_at' => now(), 'updated_at' => now()])
            );
        }

 
        $patient1Id = DB::table('patienten')->where('nummer', 'P001')->value('id');
        $patient2Id = DB::table('patienten')->where('nummer', 'P002')->value('id');
        $patient3Id = DB::table('patienten')->where('nummer', 'P003')->value('id');
        $patient4Id = DB::table('patienten')->where('nummer', 'P004')->value('id');
        $patient5Id = DB::table('patienten')->where('nummer', 'P005')->value('id');

 
        // ==================== MEDEWERKERS ====================
        $medewerker1 = Medewerker::firstOrCreate(
            ['nummer' => 'M001'],
            [
                'persoon_id' => $persoon2->id,
                'medewerker_type' => 'Tandarts',
                'specialisatie' => 'Algemene tandheelkunde',
                'is_actief' => true,
            ]
        );

 
        $medewerker2 = Medewerker::firstOrCreate(
            ['nummer' => 'M002'],
            [
                'persoon_id' => $persoon3->id,
                'medewerker_type' => 'Mondhygiënist',
                'specialisatie' => 'Preventieve zorg',
                'is_actief' => true,
            ]
        );

 
        $medewerker3 = Medewerker::firstOrCreate(
            ['nummer' => 'M003'],
            [
                'persoon_id' => $persoon4->id,
                'medewerker_type' => 'Assistent',
                'specialisatie' => null,
                'is_actief' => true,
            ]
        );

 
        $medewerker4 = Medewerker::firstOrCreate(
            ['nummer' => 'M004'],
            [
                'persoon_id' => $persoon5->id,
                'medewerker_type' => 'Praktijkmanagement',
                'specialisatie' => 'Administratie',
                'is_actief' => true,
            ]
        );

 
        // Extra medewerker
        $persoon10 = Persoon::firstOrCreate(
            ['voornaam' => 'Sophie', 'achternaam' => 'Visser'],
            [
                'gebruiker_id' => null,
                'geboortedatum' => '1989-06-12',
                'is_actief' => true,
            ]
        );

 
        $medewerker5 = Medewerker::firstOrCreate(
            ['nummer' => 'M005'],
            [
                'persoon_id' => $persoon10->id,
                'medewerker_type' => 'Tandarts',
                'specialisatie' => 'Orthodontie',
                'is_actief' => true,
            ]
        );

 
        // ==================== BESCHIKBAARHEDEN ====================
        $beschikbaarheidData = [
            [
                'medewerker_id' => $medewerker1->id,
                'datum_vanaf' => '2025-12-01',
                'datum_tot_met' => '2025-12-31',
                'tijd_vanaf' => '09:00:00',
                'tijd_tot_met' => '17:00:00',
                'status' => 'Aanwezig',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker2->id,
                'datum_vanaf' => '2025-12-01',
                'datum_tot_met' => '2025-12-31',
                'tijd_vanaf' => '08:00:00',
                'tijd_tot_met' => '16:00:00',
                'status' => 'Aanwezig',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker3->id,
                'datum_vanaf' => '2025-12-01',
                'datum_tot_met' => '2025-12-31',
                'tijd_vanaf' => '09:00:00',
                'tijd_tot_met' => '17:00:00',
                'status' => 'Aanwezig',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker1->id,
                'datum_vanaf' => '2025-12-23',
                'datum_tot_met' => '2025-12-27',
                'tijd_vanaf' => '00:00:00',
                'tijd_tot_met' => '23:59:59',
                'status' => 'Verlof',
                'is_actief' => true,
                'opmerking' => 'Kerstvakantie',
            ],
            [
                'medewerker_id' => $medewerker5->id,
                'datum_vanaf' => '2025-12-01',
                'datum_tot_met' => '2025-12-31',
                'tijd_vanaf' => '10:00:00',
                'tijd_tot_met' => '18:00:00',
                'status' => 'Aanwezig',
                'is_actief' => true,
            ],
        ];

 
        foreach ($beschikbaarheidData as $data) {
            Beschikbaarheid::firstOrCreate(
                [
                    'medewerker_id' => $data['medewerker_id'],
                    'datum_vanaf' => $data['datum_vanaf'],
                    'datum_tot_met' => $data['datum_tot_met'],
                ],
                array_merge($data, ['created_at' => now(), 'updated_at' => now()])
            );
        }

 
        // ==================== CONTACTS ====================
        $contactData = [
            [
                'patient_id' => $patient1Id,
                'straatnaam' => 'Hoofdstraat',
                'huisnummer' => '123',
                'postcode' => '1234AB',
                'plaats' => 'Amsterdam',
                'mobiel' => '0612345678',
                'email' => 'patient@gmail.com',
                'is_actief' => true,
            ],
            [
                'patient_id' => $patient2Id,
                'straatnaam' => 'Kerkstraat',
                'huisnummer' => '45',
                'postcode' => '2345BC',
                'plaats' => 'Rotterdam',
                'mobiel' => '0623456789',
                'email' => 'jan.jansen@gmail.com',
                'is_actief' => true,
            ],
            [
                'patient_id' => $patient3Id,
                'straatnaam' => 'Dorpsweg',
                'huisnummer' => '78',
                'toevoeging' => 'A',
                'postcode' => '3456CD',
                'plaats' => 'Utrecht',
                'mobiel' => '0634567890',
                'email' => 'marie.smit@gmail.com',
                'is_actief' => true,
            ],
            [
                'patient_id' => $patient4Id,
                'straatnaam' => 'Laan van Meerdervoort',
                'huisnummer' => '234',
                'postcode' => '4567DE',
                'plaats' => 'Den Haag',
                'mobiel' => '0645678901',
                'email' => 'emma.devries@gmail.com',
                'is_actief' => true,
            ],
            [
                'patient_id' => $patient5Id,
                'straatnaam' => 'Marktplein',
                'huisnummer' => '12',
                'postcode' => '5678EF',
                'plaats' => 'Eindhoven',
                'mobiel' => '0656789012',
                'email' => 'lucas.bakker@gmail.com',
                'is_actief' => true,
            ],
        ];

 
        foreach ($contactData as $data) {
            DB::table('contacts')->updateOrInsert(
                ['patient_id' => $data['patient_id']],
                array_merge($data, ['created_at' => now(), 'updated_at' => now()])
            );
        }

 
        // ==================== AFSPRAKEN ====================
        $afspraakData = [
            [
                'patient_id' => $patient1Id,
                'medewerker_id' => $medewerker1->id,
                'datum' => '2025-12-15',
                'tijd' => '10:00:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Controle afspraak',
            ],
            [
                'patient_id' => $patient2Id,
                'medewerker_id' => $medewerker2->id,
                'datum' => '2025-12-16',
                'tijd' => '14:00:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Gebitsreiniging',
            ],
            [
                'patient_id' => $patient3Id,
                'medewerker_id' => $medewerker1->id,
                'datum' => '2025-12-17',
                'tijd' => '11:30:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Vulling vervangen',
            ],
            [
                'patient_id' => $patient4Id,
                'medewerker_id' => $medewerker5->id,
                'datum' => '2025-12-18',
                'tijd' => '15:00:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Orthodontie consult',
            ],
            [
                'patient_id' => $patient5Id,
                'medewerker_id' => $medewerker2->id,
                'datum' => '2025-12-19',
                'tijd' => '09:30:00',
                'status' => 'Bevestigd',
                'is_actief' => true,
                'opmerking' => 'Periodieke controle',
            ],
        ];

 
        foreach ($afspraakData as $data) {
            DB::table('afspraken')->insert(
                array_merge($data, ['created_at' => now(), 'updated_at' => now()])
            );
        }

 
        // ==================== BEHANDELINGEN ====================
        $behandelingData = [
            [
                'medewerker_id' => $medewerker1->id,
                'patient_id' => $patient1Id,
                'datum' => '2025-11-15',
                'tijd' => '10:00:00',
                'behandeling_type' => 'Controles',
                'omschrijving' => 'Algemene controle uitgevoerd, geen problemen gevonden',
                'kosten' => 45.00,
                'status' => 'Behandeld',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker2->id,
                'patient_id' => $patient2Id,
                'datum' => '2025-11-20',
                'tijd' => '14:00:00',
                'behandeling_type' => 'Gebitsreiniging',
                'omschrijving' => 'Grondige gebitsreiniging en polijsten',
                'kosten' => 65.00,
                'status' => 'Behandeld',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker1->id,
                'patient_id' => $patient3Id,
                'datum' => '2025-11-22',
                'tijd' => '11:00:00',
                'behandeling_type' => 'Vullingen',
                'omschrijving' => 'Vulling in kies 36 geplaatst',
                'kosten' => 125.00,
                'status' => 'Behandeld',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker5->id,
                'patient_id' => $patient4Id,
                'datum' => '2025-12-18',
                'tijd' => '15:00:00',
                'behandeling_type' => 'Orthodontie',
                'omschrijving' => 'Orthodontie intake en planning besproken',
                'kosten' => 85.00,
                'status' => 'Onbehandeld',
                'is_actief' => true,
            ],
            [
                'medewerker_id' => $medewerker1->id,
                'patient_id' => $patient5Id,
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
            DB::table('behandelingen')->insert(
                array_merge($data, ['created_at' => now(), 'updated_at' => now()])
            );
        }

 
        // ==================== FACTUREN ====================
        $behandelingIds = DB::table('behandelingen')->pluck('id')->toArray();
        $factuurData = [
            [
                'nummer' => 'F2025001',
                'patient_id' => $patient1Id,
                'behandeling_id' => $behandelingIds[0] ?? null,
                'bedrag' => 45.00,
                'status' => 'Verzonden',
                'datum' => '2025-11-16',
            ],
            [
                'nummer' => 'F2025002',
                'patient_id' => $patient2Id,
                'behandeling_id' => $behandelingIds[1] ?? null,
                'bedrag' => 65.00,
                'status' => 'Verzonden',
                'datum' => '2025-11-21',
            ],
            [
                'nummer' => 'F2025003',
                'patient_id' => $patient3Id,
                'behandeling_id' => $behandelingIds[2] ?? null,
                'bedrag' => 125.00,
                'status' => 'Verzonden',
                'datum' => '2025-11-23',
            ],
            [
                'nummer' => 'F2025004',
                'patient_id' => $patient4Id,
                'behandeling_id' => $behandelingIds[3] ?? null,
                'bedrag' => 85.00,
                'status' => 'Verzonden',
                'datum' => '2025-12-19',
            ],
            [
                'nummer' => 'F2025005',
                'patient_id' => $patient5Id,
                'behandeling_id' => $behandelingIds[4] ?? null,
                'bedrag' => 350.00,
                'status' => 'Verzonden',
                'datum' => '2025-10-11',
            ],
        ];

 
        foreach ($factuurData as $data) {
            DB::table('facturen')->insert(
                array_merge($data, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}