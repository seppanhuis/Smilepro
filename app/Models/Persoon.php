<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persoon extends Model
{
    protected $table = 'personen';

    protected $fillable = [
        'voornaam',
        'achternaam',
        'geboortedatum',
        'gebruiker_id',
        'is_actief',
    ];
}
