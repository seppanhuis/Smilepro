<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medewerker extends Model
{
    protected $table = 'medewerkers';

    protected $fillable = [
        'persoon_id',
        'nummer',
        'medewerker_type',
        'specialisatie',
        'is_actief'
    ];

    /**
     * Relatie: medewerker hoort bij één persoon
     */
    public function persoon()
    {
        return $this->belongsTo(\App\Models\Persoon::class, 'persoon_id');
    }

    /**
     * Relatie: medewerker heeft meerdere afspraken
     */
    public function afspraken()
    {
        return $this->hasMany(\App\Models\Afspraak::class, 'medewerker_id');
    }
}
