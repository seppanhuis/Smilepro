<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PatientModel extends Model
{
    // No need to set $table because we will use raw SQL

    /**
     * Get all patients using raw SQL
     */
    public function getPatientData()
    {
        try {
            $result = DB::select("CALL Sp_GetAllPatiënten()");
            return $result ?? []; // array of stdClass objects
        } catch (\Exception $e) {
            return [];
        }
    }
}
