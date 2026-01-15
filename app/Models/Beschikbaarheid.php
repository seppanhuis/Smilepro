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

    /**
     * Relatie: beschikbaarheid hoort bij één user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relatie: beschikbaarheid hoort bij één medewerker
     */
    public function medewerker(): BelongsTo
    {
        return $this->belongsTo(Medewerker::class);
    }

    /**
     * Wijzig een bestaande beschikbaarheid via stored procedure
     *
     * Deze methode roept de sp_UpdateBeschikbaarheid stored procedure aan
     * die alle validatie, overlap checks en transactie management afhandelt
     *
     * @param int $beschikbaarheid_id Het ID van de beschikbaarheid die gewijzigd moet worden
     * @param array $data De nieuwe gegevens van de beschikbaarheid
     * @return array Resultaat met success boolean en eventuele error message
     */
    public static function updateViaProcedure(int $beschikbaarheid_id, array $data): array
    {
        try {
            // Roep de stored procedure aan
            \DB::statement('CALL sp_UpdateBeschikbaarheid(?, ?, ?, ?, ?, ?, ?, ?, ?, @p_success, @p_error_message)', [
                $beschikbaarheid_id,
                $data['medewerker_id'],
                $data['datum_vanaf'],
                $data['datum_tot_met'],
                $data['tijd_vanaf'],
                $data['tijd_tot_met'],
                $data['status'],
                $data['is_actief'] ?? true,
                $data['opmerking'] ?? null,
            ]);

            // Haal de output parameters op
            $result = \DB::select('SELECT @p_success as success, @p_error_message as error_message');
            $success = (bool) $result[0]->success;
            $error_message = $result[0]->error_message;

            // Log de actie
            if ($success) {
                \Log::info('Beschikbaarheid succesvol gewijzigd', [
                    'beschikbaarheid_id' => $beschikbaarheid_id,
                ]);
            }

            // Controleer of er een foutmelding is
            if ($error_message !== null) {
                return [
                    'success' => false,
                    'message' => $error_message,
                ];
            }

            return [
                'success' => true,
                'message' => 'Beschikbaarheid succesvol gewijzigd',
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            // Log technische fout
            \Log::error('Database fout bij wijzigen beschikbaarheid', [
                'beschikbaarheid_id' => $beschikbaarheid_id,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);

            return [
                'success' => false,
                'message' => 'Er is een technische fout opgetreden bij het wijzigen van de beschikbaarheid',
            ];
        } catch (\Exception $e) {
            // Log algemene fout
            \Log::error('Algemene fout bij wijzigen beschikbaarheid', [
                'beschikbaarheid_id' => $beschikbaarheid_id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Er is een onverwachte fout opgetreden',
            ];
        }
    }

    /**
     * Verwijder een bestaande beschikbaarheid via stored procedure
     *
     * Deze methode roept de sp_DeleteBeschikbaarheid stored procedure aan
     * die controleert of de beschikbaarheid verwijderd mag worden
     *
     * @param int $beschikbaarheid_id Het ID van de beschikbaarheid die verwijderd moet worden
     * @param int $medewerker_id Het ID van de medewerker voor verificatie
     * @return array Resultaat met success boolean en eventuele error message
     */
    public static function deleteViaProcedure(int $beschikbaarheid_id, int $medewerker_id): array
    {
        try {
            // Roep de stored procedure aan
            \DB::statement('CALL sp_DeleteBeschikbaarheid(?, ?, @p_success, @p_error_message)', [
                $beschikbaarheid_id,
                $medewerker_id,
            ]);

            // Haal de output parameters op
            $result = \DB::select('SELECT @p_success as success, @p_error_message as error_message');
            $success = (bool) $result[0]->success;
            $error_message = $result[0]->error_message;

            // Log de actie
            if ($success) {
                \Log::info('Beschikbaarheid succesvol verwijderd', [
                    'beschikbaarheid_id' => $beschikbaarheid_id,
                    'medewerker_id' => $medewerker_id,
                ]);
            }

            // Controleer of er een foutmelding is
            if ($error_message !== null) {
                return [
                    'success' => false,
                    'message' => $error_message,
                ];
            }

            return [
                'success' => true,
                'message' => 'Beschikbaarheid succesvol verwijderd',
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            // Log technische fout
            \Log::error('Database fout bij verwijderen beschikbaarheid', [
                'beschikbaarheid_id' => $beschikbaarheid_id,
                'medewerker_id' => $medewerker_id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Er is een technische fout opgetreden bij het verwijderen van de beschikbaarheid',
            ];
        } catch (\Exception $e) {
            // Log algemene fout
            \Log::error('Algemene fout bij verwijderen beschikbaarheid', [
                'beschikbaarheid_id' => $beschikbaarheid_id,
                'medewerker_id' => $medewerker_id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Er is een onverwachte fout opgetreden',
            ];
        }
    }
}
