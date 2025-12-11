<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Afspraak extends Model
{
    protected $table = 'afspraken';

    protected $fillable = [
        'patient_id',
        'medewerker_id',
        'datum',
        'tijd',
        'status',
        'is_actief',
        'opmerking',
    ];

    public function medewerker()
    {
        return $this->belongsTo(Medewerker::class, 'medewerker_id');
    }
}
