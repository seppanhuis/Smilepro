<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request voor het valideren van medewerker update data
 *
 * Deze klasse bevat alle validatie regels en berichten
 * voor het wijzigen van een medewerker
 *
 * Volgt PSR-12 coding standards
 */
class UpdateMedewerkerRequest extends FormRequest
{
    /**
     * Bepaal of de gebruiker geautoriseerd is om deze request te maken
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Autorisatie wordt al gecontroleerd door de role:praktijkmanagement middleware
        return true;
    }

    /**
     * Validatie regels voor het wijzigen van een medewerker
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $medewerker = $this->route('medewerker');
        // Haal de gebruiker_id op via de persoon relatie voor de unique check
        $gebruikerId = $medewerker->persoon->gebruiker_id ?? null;

        return [
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:users,email,' . $gebruikerId,
            ],
            'voornaam' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s\-]+$/', // Alleen letters, spaties en koppeltekens
            ],
            'tussenvoegsel' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s\-]+$/',
            ],
            'achternaam' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s\-]+$/',
            ],
            'geboortedatum' => [
                'required',
                'date',
                'before:today',
                'after:' . now()->subYears(100)->format('Y-m-d'), // Max 100 jaar oud
            ],
            'medewerker_type' => [
                'required',
                'string',
                'in:Tandarts,Mondhygiënist,Assistent,Orthodontist',
            ],
            'specialisatie' => [
                'nullable',
                'string',
                'max:255',
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
            'email.required' => 'Email is verplicht',
            'email.email' => 'Ongeldig email adres',
            'email.max' => 'Email mag maximaal 255 karakters bevatten',
            'email.unique' => 'Deze email is al in gebruik',
            'voornaam.required' => 'Voornaam is verplicht',
            'voornaam.min' => 'Voornaam moet minimaal 2 karakters bevatten',
            'voornaam.max' => 'Voornaam mag maximaal 100 karakters bevatten',
            'voornaam.regex' => 'Voornaam mag alleen letters, spaties en koppeltekens bevatten',
            'tussenvoegsel.max' => 'Tussenvoegsel mag maximaal 50 karakters bevatten',
            'tussenvoegsel.regex' => 'Tussenvoegsel mag alleen letters, spaties en koppeltekens bevatten',
            'achternaam.required' => 'Achternaam is verplicht',
            'achternaam.min' => 'Achternaam moet minimaal 2 karakters bevatten',
            'achternaam.max' => 'Achternaam mag maximaal 100 karakters bevatten',
            'achternaam.regex' => 'Achternaam mag alleen letters, spaties en koppeltekens bevatten',
            'geboortedatum.required' => 'Geboortedatum is verplicht',
            'geboortedatum.date' => 'Ongeldige datum',
            'geboortedatum.before' => 'Geboortedatum moet in het verleden liggen',
            'geboortedatum.after' => 'Geboortedatum is te ver in het verleden',
            'medewerker_type.required' => 'Medewerker type is verplicht',
            'medewerker_type.in' => 'Ongeldig medewerker type geselecteerd',
            'specialisatie.max' => 'Specialisatie mag maximaal 255 karakters bevatten',
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
            'email' => 'email adres',
            'voornaam' => 'voornaam',
            'tussenvoegsel' => 'tussenvoegsel',
            'achternaam' => 'achternaam',
            'geboortedatum' => 'geboortedatum',
            'medewerker_type' => 'medewerker type',
            'specialisatie' => 'specialisatie',
            'is_actief' => 'actief status',
            'opmerking' => 'opmerking',
        ];
    }
}
