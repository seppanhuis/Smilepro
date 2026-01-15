<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request voor het valideren van beschikbaarheid update data
 *
 * Deze klasse bevat alle validatie regels en berichten
 * voor het wijzigen van beschikbaarheid
 *
 * Volgt PSR-12 coding standards
 */
class UpdateBeschikbaarheidRequest extends FormRequest
{
    /**
     * Bepaal of de gebruiker geautoriseerd is om deze request te maken
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Alleen administrators mogen beschikbaarheid wijzigen
        return $this->user() && $this->user()->hasRole('Administrator');
    }

    /**
     * Validatie regels voor het wijzigen van beschikbaarheid
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'medewerker_id' => [
                'required',
                'integer',
                'exists:medewerkers,id',
            ],
            'datum_vanaf' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'datum_tot_met' => [
                'required',
                'date',
                'after_or_equal:datum_vanaf',
            ],
            'tijd_vanaf' => [
                'required',
                'date_format:H:i',
            ],
            'tijd_tot_met' => [
                'required',
                'date_format:H:i',
                'after:tijd_vanaf',
            ],
            'status' => [
                'required',
                'string',
                'in:Beschikbaar,Niet beschikbaar,Vakantie',
            ],
            'is_actief' => [
                'boolean',
            ],
            'opmerking' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Custom validatie berichten in het Nederlands
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'medewerker_id.required' => 'Medewerker is verplicht',
            'medewerker_id.integer' => 'Ongeldig medewerker ID',
            'medewerker_id.exists' => 'Geselecteerde medewerker bestaat niet',
            'datum_vanaf.required' => 'Start datum is verplicht',
            'datum_vanaf.date' => 'Ongeldige start datum',
            'datum_vanaf.after_or_equal' => 'Start datum moet vandaag of in de toekomst zijn',
            'datum_tot_met.required' => 'Eind datum is verplicht',
            'datum_tot_met.date' => 'Ongeldige eind datum',
            'datum_tot_met.after_or_equal' => 'Eind datum moet na of gelijk aan start datum zijn',
            'tijd_vanaf.required' => 'Start tijd is verplicht',
            'tijd_vanaf.date_format' => 'Ongeldige tijd format voor start tijd (gebruik HH:MM)',
            'tijd_tot_met.required' => 'Eind tijd is verplicht',
            'tijd_tot_met.date_format' => 'Ongeldige tijd format voor eind tijd (gebruik HH:MM)',
            'tijd_tot_met.after' => 'Eind tijd moet na start tijd zijn',
            'status.required' => 'Status is verplicht',
            'status.in' => 'Ongeldige status geselecteerd',
            'is_actief.boolean' => 'Actief status moet true of false zijn',
            'opmerking.max' => 'Opmerking mag maximaal 1000 karakters bevatten',
        ];
    }

    /**
     * Custom attribute namen voor validatie berichten
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'medewerker_id' => 'medewerker',
            'datum_vanaf' => 'start datum',
            'datum_tot_met' => 'eind datum',
            'tijd_vanaf' => 'start tijd',
            'tijd_tot_met' => 'eind tijd',
            'status' => 'status',
            'is_actief' => 'actief status',
            'opmerking' => 'opmerking',
        ];
    }

    /**
     * Voer extra validatie uit na de standaard validatie
     *
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Controleer of de datum en tijd combinatie geldig is
            if ($this->datum_vanaf === $this->datum_tot_met) {
                // Als het dezelfde dag is, controleer of de tijden kloppen
                if ($this->tijd_vanaf >= $this->tijd_tot_met) {
                    $validator->errors()->add(
                        'tijd_tot_met',
                        'Eind tijd moet na start tijd zijn op dezelfde dag'
                    );
                }
            }
        });
    }
}
