<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medewerker extends Model
{
    protected $table = 'medewerkers';

    protected $fillable = [
        'persoon_id',
        'nummer',
        'medewerker_type',
        'specialisatie',
        'is_actief',
        'opmerking',
    ];

    protected $casts = [
        'is_actief' => 'boolean',
    ];

    /**
     * Accessor: volledige naam van de medewerker
     */
    public function getFullNameAttribute(): string
    {
        if (!$this->persoon) {
            return 'N/A';
        }

        $parts = array_filter([
            $this->persoon->voornaam,
            $this->persoon->tussenvoegsel,
            $this->persoon->achternaam,
        ]);

        return implode(' ', $parts);
    }

    /**
     * Relatie: medewerker hoort bij één persoon
     */
    public function persoon(): BelongsTo
    {
        return $this->belongsTo(Persoon::class, 'persoon_id');
    }

    /**
     * Relatie: medewerker heeft meerdere afspraken
     */
    public function afspraken(): HasMany
    {
        return $this->hasMany(Afspraak::class, 'medewerker_id');
    }

    /**
     * Genereer een nieuw medewerkernummer
     *
     * Genereert een uniek medewerkernummer in het formaat MDW####
     *
     * @return string Het gegenereerde medewerkernummer
     */
    public static function generateNummer(): string
    {
        $laatsteMedewerker = self::orderBy('id', 'desc')->first();
        $volgNummer = $laatsteMedewerker ? (intval(substr($laatsteMedewerker->nummer, 3)) + 1) : 1;

        return 'MDW' . str_pad($volgNummer, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Maak een nieuwe medewerker aan via stored procedure
     *
     * Deze methode roept de sp_CreateMedewerker stored procedure aan
     * die alle validatie, transactie management en database inserts afhandelt
     *
     * @param array $data Array met medewerkergegevens
     * @return array Array met 'success' boolean en 'message' of 'medewerker_id'
     * @throws \Exception Als er een onverwachte fout optreedt
     */
    public static function createViaProcedure(array $data): array
    {
        try {
            // Roep de stored procedure aan
            \DB::statement('CALL sp_CreateMedewerker(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @p_medewerker_id, @p_error_message)', [
                $data['gebruikersnaam'],
                $data['email'],
                $data['password'], // Moet al gehasht zijn
                $data['rol_naam'],
                $data['voornaam'],
                $data['tussenvoegsel'] ?? null,
                $data['achternaam'],
                $data['geboortedatum'],
                $data['nummer'],
                $data['medewerker_type'],
                $data['specialisatie'] ?? null,
                $data['opmerking'] ?? null,
            ]);

            // Haal de output parameters op
            $result = \DB::select('SELECT @p_medewerker_id as medewerker_id, @p_error_message as error_message');
            $medewerker_id = $result[0]->medewerker_id;
            $error_message = $result[0]->error_message;

            // Controleer of er een foutmelding is
            if ($error_message !== null) {
                return [
                    'success' => false,
                    'message' => $error_message,
                ];
            }

            return [
                'success' => true,
                'medewerker_id' => $medewerker_id,
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            // Controleer op specifieke database fouten
            if (str_contains($e->getMessage(), 'Duplicate entry') ||
                str_contains($e->getMessage(), 'UNIQUE constraint')) {
                return [
                    'success' => false,
                    'message' => 'Deze medewerker bestaat al',
                ];
            }

            throw $e; // Gooi andere database fouten door
        }
    }
}
