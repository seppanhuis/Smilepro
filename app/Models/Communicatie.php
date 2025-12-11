<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Communicatie extends Model
{
    protected $table = 'communicaties';

    protected $fillable = [
        'afzender_id',
        'ontvanger_id',
        'bericht',
        'datum',
        'is_actief',
        'opmerking'
    ];

    // Haal unieke medewerkers voor een patient via stored procedure
    public function getMedewerkersVoorPatient($patientId)
    {
        return DB::select('CALL sp_GetGesprekkenVanPatient(?)', [$patientId]);
    }

    // Haal alle berichten met een medewerker via stored procedure
    public function getBerichtenMetMedewerker($patientId, $medewerkerId)
    {
        return DB::select('CALL sp_GetBerichtenPatientMedewerker(?, ?)', [$patientId, $medewerkerId]);
    }
}
