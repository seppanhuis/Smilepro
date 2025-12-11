<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patienten';

    protected $fillable = [
        'persoon_id',
        'nummer',
        'medisch_dossier',
        'is_actief'
    ];
}
