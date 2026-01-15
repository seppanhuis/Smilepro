<?php

namespace App\Livewire;

use App\Models\Medewerker;
use App\Models\Persoon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

/**
 * Livewire component voor het beheren van medewerkers
 *
 * Deze component biedt functionaliteit voor:
 * - Tonen van medewerkers lijst
 * - Wijzigen van bestaande medewerkers
 * - Verwijderen van medewerkers
 *
 * Volgt PSR-12 coding standards
 */
class MedewerkerBeheer extends Component
{
    use WithPagination;

    // Properties voor het wijzigen van een medewerker
    public $medewerker_id;
    public $email;
    public $voornaam;
    public $tussenvoegsel;
    public $achternaam;
    public $geboortedatum;
    public $medewerker_type;
    public $specialisatie;
    public $is_actief;
    public $opmerking;

    // UI state properties
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $deleteConfirmId = null;

    // Zoek en filter properties
    public $search = '';
    public $filterActief = 'all';

    /**
     * Validatie regels voor server-side validatie
     * Bevat uitgebreide validatie voor alle velden
     */
    protected function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'voornaam' => 'required|string|min:2|max:100',
            'tussenvoegsel' => 'nullable|string|max:50',
            'achternaam' => 'required|string|min:2|max:100',
            'geboortedatum' => 'required|date|before:today',
            'medewerker_type' => 'required|string|in:Tandarts,Mondhygiënist,Assistent,Orthodontist',
            'specialisatie' => 'nullable|string|max:255',
            'is_actief' => 'boolean',
            'opmerking' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Custom validatie berichten in het Nederlands
     */
    protected function messages(): array
    {
        return [
            'email.required' => 'Email is verplicht',
            'email.email' => 'Ongeldig email adres',
            'email.max' => 'Email mag maximaal 255 karakters bevatten',
            'voornaam.required' => 'Voornaam is verplicht',
            'voornaam.min' => 'Voornaam moet minimaal 2 karakters bevatten',
            'voornaam.max' => 'Voornaam mag maximaal 100 karakters bevatten',
            'tussenvoegsel.max' => 'Tussenvoegsel mag maximaal 50 karakters bevatten',
            'achternaam.required' => 'Achternaam is verplicht',
            'achternaam.min' => 'Achternaam moet minimaal 2 karakters bevatten',
            'achternaam.max' => 'Achternaam mag maximaal 100 karakters bevatten',
            'geboortedatum.required' => 'Geboortedatum is verplicht',
            'geboortedatum.date' => 'Ongeldige datum',
            'geboortedatum.before' => 'Geboortedatum moet in het verleden liggen',
            'medewerker_type.required' => 'Medewerker type is verplicht',
            'medewerker_type.in' => 'Ongeldig medewerker type',
            'specialisatie.max' => 'Specialisatie mag maximaal 255 karakters bevatten',
            'opmerking.max' => 'Opmerking mag maximaal 1000 karakters bevatten',
        ];
    }

    /**
     * Open het wijzig modal voor een specifieke medewerker
     *
     * @param int $id Het ID van de medewerker die gewijzigd moet worden
     * @return void
     */
    public function editMedewerker(int $id): void
    {
        try {
            $medewerker = Medewerker::with(['persoon.user'])->findOrFail($id);

            // Vul de properties met de huidige gegevens
            $this->medewerker_id = $medewerker->id;
            $this->email = $medewerker->persoon->user->email;
            $this->voornaam = $medewerker->persoon->voornaam;
            $this->tussenvoegsel = $medewerker->persoon->tussenvoegsel;
            $this->achternaam = $medewerker->persoon->achternaam;
            $this->geboortedatum = $medewerker->persoon->geboortedatum?->format('Y-m-d');
            $this->medewerker_type = $medewerker->medewerker_type;
            $this->specialisatie = $medewerker->specialisatie;
            $this->is_actief = $medewerker->is_actief;
            $this->opmerking = $medewerker->opmerking;

            $this->showEditModal = true;
        } catch (\Exception $e) {
            Log::error('Fout bij laden medewerker voor wijzigen', [
                'medewerker_id' => $id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Medewerker kon niet worden geladen');
        }
    }

    /**
     * Sla de gewijzigde medewerkergegevens op
     * Bevat server-side validatie en gebruikt stored procedure
     *
     * @return void
     */
    public function updateMedewerker(): void
    {
        // Server-side validatie
        $this->validate();

        try {
            // Roep de stored procedure aan via het model
            $result = Medewerker::updateViaProcedure($this->medewerker_id, [
                'email' => $this->email,
                'voornaam' => $this->voornaam,
                'tussenvoegsel' => $this->tussenvoegsel,
                'achternaam' => $this->achternaam,
                'geboortedatum' => $this->geboortedatum,
                'medewerker_type' => $this->medewerker_type,
                'specialisatie' => $this->specialisatie,
                'is_actief' => $this->is_actief ?? true,
                'opmerking' => $this->opmerking,
            ]);

            if ($result['success']) {
                // Sluit modal en toon succesmelding
                $this->showEditModal = false;
                $this->reset(['medewerker_id', 'email', 'voornaam', 'tussenvoegsel', 'achternaam', 'geboortedatum', 'medewerker_type', 'specialisatie', 'is_actief', 'opmerking']);
                session()->flash('success', $result['message']);

                // Dispatch event voor frontend notificatie
                $this->dispatch('medewerker-updated');
            } else {
                // Toon foutmelding aan gebruiker
                session()->flash('error', $result['message']);
            }
        } catch (\Exception $e) {
            Log::error('Onverwachte fout bij updaten medewerker', [
                'medewerker_id' => $this->medewerker_id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Er is een onverwachte fout opgetreden');
        }
    }

    /**
     * Open het verwijder bevestigingsmodal
     *
     * @param int $id Het ID van de medewerker die verwijderd moet worden
     * @return void
     */
    public function confirmDelete(int $id): void
    {
        $this->deleteConfirmId = $id;
        $this->showDeleteModal = true;
    }

    /**
     * Verwijder een medewerker via stored procedure
     * Controleert of medewerker verwijderd mag worden
     *
     * @return void
     */
    public function deleteMedewerker(): void
    {
        if (!$this->deleteConfirmId) {
            return;
        }

        try {
            // Roep de stored procedure aan via het model
            $result = Medewerker::deleteViaProcedure($this->deleteConfirmId);

            if ($result['success']) {
                // Sluit modal en toon succesmelding
                $this->showDeleteModal = false;
                $this->deleteConfirmId = null;
                session()->flash('success', $result['message']);

                // Dispatch event voor frontend notificatie
                $this->dispatch('medewerker-deleted');
            } else {
                // Toon foutmelding aan gebruiker
                session()->flash('error', $result['message']);
            }
        } catch (\Exception $e) {
            Log::error('Onverwachte fout bij verwijderen medewerker', [
                'medewerker_id' => $this->deleteConfirmId,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Er is een onverwachte fout opgetreden');
        }
    }

    /**
     * Annuleer en sluit modals
     *
     * @return void
     */
    public function closeModal(): void
    {
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->deleteConfirmId = null;
        $this->resetValidation();
    }

    /**
     * Render de component met gepagineerde medewerkers lijst
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $query = Medewerker::with(['persoon.user'])
            ->when($this->search, function ($q) {
                $q->whereHas('persoon', function ($query) {
                    $query->where('voornaam', 'like', '%' . $this->search . '%')
                        ->orWhere('achternaam', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterActief !== 'all', function ($q) {
                $q->where('is_actief', $this->filterActief === 'actief');
            });

        return view('livewire.medewerker-beheer', [
            'medewerkers' => $query->paginate(10),
        ]);
    }
}
