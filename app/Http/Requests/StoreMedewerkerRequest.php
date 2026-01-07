<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreMedewerkerRequest extends FormRequest
{
    /**
     * Bepaal of de gebruiker gemachtigd is om deze aanvraag te doen
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Alleen gebruikers met rol 'Praktijkmanagement' mogen medewerkers aanmaken
        // Case-insensitive check voor flexibiliteit
        if (!$this->user()) {
            return false;
        }

        $userRole = strtolower($this->user()->rol_naam ?? '');
        $allowedRoles = ['praktijkmanagement'];

        return in_array($userRole, $allowedRoles);
    }


    public function rules(): array
    {
        return [
            // Gebruikersgegevens
            'gebruikersnaam' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z0-9\s]+$/',
            ],
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
            'rol_naam' => [
                'required',
                'string',
                'in:Assistent,Mondhygiënist,Tandarts,Praktijkmanagement',
            ],

            // Persoonsgegevens
            'voornaam' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s-]+$/',
            ],
            'tussenvoegsel' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'achternaam' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s-]+$/',
            ],
            'geboortedatum' => [
                'required',
                'date',
                'before:today',
                'after:1920-01-01',
            ],

            // Medewerkergegevens
            'nummer' => [
                'required',
                'string',
                'max:50',
                'unique:medewerkers,nummer',
                'regex:/^[A-Z0-9-]+$/',
            ],
            'medewerker_type' => [
                'required',
                'string',
                'in:Assistent,Mondhygiënist,Tandarts,Praktijkmanagement',
            ],
            'specialisatie' => [
                'nullable',
                'string',
                'max:255',
            ],
            'opmerking' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Verkrijg aangepaste foutmeldingen voor validatie
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Gebruikersnaam meldingen
            'gebruikersnaam.required' => 'De gebruikersnaam is verplicht.',
            'gebruikersnaam.min' => 'De gebruikersnaam moet minimaal 3 karakters bevatten.',
            'gebruikersnaam.max' => 'De gebruikersnaam mag maximaal 255 karakters bevatten.',
            'gebruikersnaam.regex' => 'De gebruikersnaam mag alleen letters, cijfers en spaties bevatten.',

            // Email meldingen
            'email.required' => 'Het e-mailadres is verplicht.',
            'email.unique' => 'Deze medewerker bestaat al.',
            'email.max' => 'Het e-mailadres mag maximaal 255 karakters bevatten.',

            // Wachtwoord meldingen
            'password.required' => 'Het wachtwoord is verplicht.',
            'password.confirmed' => 'De wachtwoorden komen niet overeen.',

            // Rol meldingen
            'rol_naam.required' => 'De rol is verplicht.',
            'rol_naam.in' => 'De geselecteerde rol is ongeldig.',

            // Voornaam meldingen
            'voornaam.required' => 'De voornaam is verplicht.',
            'voornaam.min' => 'De voornaam moet minimaal 2 karakters bevatten.',
            'voornaam.max' => 'De voornaam mag maximaal 100 karakters bevatten.',
            'voornaam.regex' => 'De voornaam mag alleen letters, spaties en streepjes bevatten.',

            // Tussenvoegsel meldingen
            'tussenvoegsel.max' => 'Het tussenvoegsel mag maximaal 50 karakters bevatten.',
            'tussenvoegsel.regex' => 'Het tussenvoegsel mag alleen letters en spaties bevatten.',

            // Achternaam meldingen
            'achternaam.required' => 'De achternaam is verplicht.',
            'achternaam.min' => 'De achternaam moet minimaal 2 karakters bevatten.',
            'achternaam.max' => 'De achternaam mag maximaal 100 karakters bevatten.',
            'achternaam.regex' => 'De achternaam mag alleen letters, spaties en streepjes bevatten.',

            // Geboortedatum meldingen
            'geboortedatum.required' => 'De geboortedatum is verplicht.',
            'geboortedatum.date' => 'De geboortedatum moet een geldige datum zijn.',
            'geboortedatum.before' => 'De geboortedatum moet in het verleden liggen.',
            'geboortedatum.after' => 'De geboortedatum moet na 1920 liggen.',

            // Nummer meldingen
            'nummer.required' => 'Het medewerkernummer is verplicht.',
            'nummer.unique' => 'Dit medewerkernummer bestaat al.',
            'nummer.max' => 'Het medewerkernummer mag maximaal 50 karakters bevatten.',
            'nummer.regex' => 'Het medewerkernummer mag alleen hoofdletters, cijfers en streepjes bevatten.',

            // Medewerker type meldingen
            'medewerker_type.required' => 'Het medewerkertype is verplicht.',
            'medewerker_type.in' => 'Het geselecteerde medewerkertype is ongeldig.',

            // Specialisatie meldingen
            'specialisatie.max' => 'De specialisatie mag maximaal 255 karakters bevatten.',

            // Opmerking meldingen
            'opmerking.max' => 'De opmerking mag maximaal 1000 karakters bevatten.',
        ];
    }

    /**
     * Verkrijg aangepaste attributen voor validatie fouten
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'gebruikersnaam' => 'gebruikersnaam',
            'email' => 'e-mailadres',
            'password' => 'wachtwoord',
            'rol_naam' => 'rol',
            'voornaam' => 'voornaam',
            'tussenvoegsel' => 'tussenvoegsel',
            'achternaam' => 'achternaam',
            'geboortedatum' => 'geboortedatum',
            'nummer' => 'medewerkernummer',
            'medewerker_type' => 'medewerkertype',
            'specialisatie' => 'specialisatie',
            'opmerking' => 'opmerking',
        ];
    }

    /**
     * Bereid de data voor validatie voor
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Trim en sanitize alle string inputs
        $this->merge([
            'gebruikersnaam' => $this->gebruikersnaam ? trim($this->gebruikersnaam) : null,
            'email' => $this->email ? strtolower(trim($this->email)) : null,
            'voornaam' => $this->voornaam ? trim($this->voornaam) : null,
            'tussenvoegsel' => $this->tussenvoegsel ? trim($this->tussenvoegsel) : null,
            'achternaam' => $this->achternaam ? trim($this->achternaam) : null,
            'nummer' => $this->nummer ? strtoupper(trim($this->nummer)) : null,
            'specialisatie' => $this->specialisatie ? trim($this->specialisatie) : null,
            'opmerking' => $this->opmerking ? trim($this->opmerking) : null,
        ]);
    }
}
