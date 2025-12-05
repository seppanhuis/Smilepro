<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beschikbaarheid extends Model
{
    protected $table = 'beschikbaarheden';

    protected $fillable = [
        'user_id',
        'medewerker_id',
        'datum_vanaf',
        'datum_tot_met',
        'tijd_vanaf',
        'tijd_tot_met',
        'status',
        'is_actief',
        'opmerking',
    ];

    protected $casts = [
        'datum_vanaf' => 'date',
        'datum_tot_met' => 'date',
        'is_actief' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medewerker(): BelongsTo
    {
        return $this->belongsTo(Medewerker::class);
    }
}
