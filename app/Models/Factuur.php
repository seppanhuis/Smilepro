<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

/**
 * Model voor facturen
 *
 * Representeert een factuur in het systeem die gekoppeld is aan een patiënt
 * en een behandeling. Bevat informatie over factuurnummer, datum, bedrag en status.
 */
class Factuur extends Model
{
    /**
     * De tabel die bij dit model hoort
     *
     * @var string
     */
    protected $table = 'facturen';

    /**
     * Kolommen die mass-assignable zijn
     *
     * @var array<string>
     */
    protected $fillable = [
        'patient_id',
        'behandeling_id',
        'nummer',
        'datum',
        'bedrag',
        'status',
        'is_actief',
        'opmerking',
    ];

    /**
     * Type casting voor attributen
     *
     * @var array<string, string>
     */
    protected $casts = [
        'datum' => 'date',
        'bedrag' => 'decimal:2',
        'is_actief' => 'boolean',
    ];

    /**
     * Relatie met de patiënt
     *
     * Elke factuur hoort bij één patiënt
     *
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relatie met de behandeling
     *
     * Elke factuur hoort bij één behandeling
     *
     * @return BelongsTo
     */
    public function behandeling(): BelongsTo
    {
        return $this->belongsTo(Behandeling::class);
    }

    /**
     * Haal alle facturen op met relaties
     *
     * Haalt alle facturen op inclusief patiënt- en behandelingsinformatie,
     * gesorteerd op datum (nieuwste eerst).
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllFacturen()
    {
        try {
            return self::with(['patient.persoon', 'behandeling'])
                ->orderBy('datum', 'desc')
                ->get();
        } catch (\Exception $e) {
            // Log de fout voor debugging
            Log::error('Fout bij ophalen facturen in model: ' . $e->getMessage());

            // Retourneer lege collectie bij fout
            return collect();
        }
    }

    /**
     * Genereer het volgende factuurnummer
     *
     * Format: INV-{JAAR}-{VOLGNUMMER}
     * Bijvoorbeeld: INV-2026-001
     *
     * @return string
     */
    public static function generateNextInvoiceNumber()
    {
        $year = date('Y');
        $prefix = "INV-{$year}-";

        // Haal het laatste factuurnummer op voor dit jaar
        $lastInvoice = self::where('nummer', 'LIKE', $prefix . '%')
            ->orderBy('nummer', 'desc')
            ->first();

        if ($lastInvoice) {
            // Haal het volgnummer uit het laatste factuurnummer
            $lastNumber = (int) str_replace($prefix, '', $lastInvoice->nummer);
            $nextNumber = $lastNumber + 1;
        } else {
            // Eerste factuur van dit jaar
            $nextNumber = 1;
        }

        // Format met leading zeros (3 cijfers)
        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
