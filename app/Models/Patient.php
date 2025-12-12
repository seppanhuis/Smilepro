<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $table = 'patienten';

    protected $fillable = [
        'persoon_id',
        'nummer',
        'medisch_dossier',
        'is_actief'
    ];

    public function persoon(): BelongsTo
    {
        return $this->belongsTo(Persoon::class);
    }
}
