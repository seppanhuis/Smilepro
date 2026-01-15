<?php

namespace App\Livewire;

use App\Models\Beschikbaarheid;
use App\Models\Medewerker;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Livewire component voor het beheren van beschikbaarheid
 *
 * Deze component biedt functionaliteit voor:
 * - Tonen van beschikbaarheid lijst
 * - Wijzigen van bestaande beschikbaarheid
 * - Verwijderen van beschikbaarheid
 *
 * Volgt PSR-12 coding standards
 */
class BeschikbaarheidBeheer extends Component
{
    use WithPagination;

    // Properties voor het wijzigen van beschikbaarheid
    public $beschikbaarheid_id;
    public $medewerker_id;
    public $datum_vanaf;
    public $datum_tot_met;
    public $tijd_vanaf;
    public $tijd_tot_met;
    public $status;
    public $is_actief;
    public $opmerking;

    // UI state properties
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $deleteConfirmId = null;

    // Zoek en filter properties
    public $filterMedewerker = 'all';

    /**
     * Validatie regels voor server-side validatie
     * Bevat uitgebreide validatie inclusief datum en tijd checks
     */
    protected function rules(): array
    {
        return [
            'medewerker_id' => 'required|exists:medewerkers,id',
            'datum_vanaf' => 'required|date|after_or_equal:today',
            'datum_tot_met' => 'required|date|after_or_equal:datum_vanaf',
            'tijd_vanaf' => 'required|date_format:H:i',
            'tijd_tot_met' => 'required|date_format:H:i|after:tijd_vanaf',
            'status' => 'required|string|in:Beschikbaar,Niet beschikbaar,Vakantie',
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
            'medewerker_id.required' => 'Medewerker is verplicht',
            'medewerker_id.exists' => 'Geselecteerde medewerker bestaat niet',
            'datum_vanaf.required' => 'Start datum is verplicht',
            'datum_vanaf.date' => 'Ongeldige datum',
            'datum_vanaf.after_or_equal' => 'Start datum moet vandaag of in de toekomst zijn',
            'datum_tot_met.required' => 'Eind datum is verplicht',
            'datum_tot_met.date' => 'Ongeldige datum',
            'datum_tot_met.after_or_equal' => 'Eind datum moet na of gelijk aan start datum zijn',
            'tijd_vanaf.required' => 'Start tijd is verplicht',
            'tijd_vanaf.date_format' => 'Ongeldige tijd format (HH:MM)',
            'tijd_tot_met.required' => 'Eind tijd is verplicht',
            'tijd_tot_met.date_format' => 'Ongeldige tijd format (HH:MM)',
            'tijd_tot_met.after' => 'Eind tijd moet na start tijd zijn',
            'status.required' => 'Status is verplicht',
            'status.in' => 'Ongeldige status',
            'opmerking.max' => 'Opmerking mag maximaal 1000 karakters bevatten',
        ];
    }

    /**
     * Open het wijzig modal voor een specifieke beschikbaarheid
     *
     * @param int $id Het ID van de beschikbaarheid die gewijzigd moet worden
     * @return void
     */
    public function editBeschikbaarheid(int $id): void
    {
        try {
            $beschikbaarheid = Beschikbaarheid::with('medewerker')->findOrFail($id);

            // Vul de properties met de huidige gegevens
            $this->beschikbaarheid_id = $beschikbaarheid->id;
            $this->medewerker_id = $beschikbaarheid->medewerker_id;
            $this->datum_vanaf = $beschikbaarheid->datum_vanaf->format('Y-m-d');
            $this->datum_tot_met = $beschikbaarheid->datum_tot_met->format('Y-m-d');
            $this->tijd_vanaf = $beschikbaarheid->tijd_vanaf;
            $this->tijd_tot_met = $beschikbaarheid->tijd_tot_met;
            $this->status = $beschikbaarheid->status;
            $this->is_actief = $beschikbaarheid->is_actief;
            $this->opmerking = $beschikbaarheid->opmerking;

            $this->showEditModal = true;
        } catch (\Exception $e) {
            Log::error('Fout bij laden beschikbaarheid voor wijzigen', [
                'beschikbaarheid_id' => $id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Beschikbaarheid kon niet worden geladen');
        }
    }

    /**
     * Sla de gewijzigde beschikbaarheid op
     * Bevat server-side validatie en gebruikt stored procedure
     *
     * @return void
     */
    public function updateBeschikbaarheid(): void
    {
        // Server-side validatie
        $this->validate();

        try {
            // Roep de stored procedure aan via het model
            $result = Beschikbaarheid::updateViaProcedure($this->beschikbaarheid_id, [
                'medewerker_id' => $this->medewerker_id,
                'datum_vanaf' => $this->datum_vanaf,
                'datum_tot_met' => $this->datum_tot_met,
                'tijd_vanaf' => $this->tijd_vanaf,
                'tijd_tot_met' => $this->tijd_tot_met,
                'status' => $this->status,
                'is_actief' => $this->is_actief ?? true,
                'opmerking' => $this->opmerking,
            ]);

            if ($result['success']) {
                // Sluit modal en toon succesmelding
                $this->showEditModal = false;
                $this->reset(['beschikbaarheid_id', 'medewerker_id', 'datum_vanaf', 'datum_tot_met', 'tijd_vanaf', 'tijd_tot_met', 'status', 'is_actief', 'opmerking']);
                session()->flash('success', $result['message']);

                // Dispatch event voor frontend notificatie
                $this->dispatch('beschikbaarheid-updated');
            } else {
                // Toon foutmelding aan gebruiker
                session()->flash('error', $result['message']);
            }
        } catch (\Exception $e) {
            Log::error('Onverwachte fout bij updaten beschikbaarheid', [
                'beschikbaarheid_id' => $this->beschikbaarheid_id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Er is een onverwachte fout opgetreden');
        }
    }

    /**
     * Open het verwijder bevestigingsmodal
     *
     * @param int $id Het ID van de beschikbaarheid die verwijderd moet worden
     * @return void
     */
    public function confirmDelete(int $id): void
    {
        $this->deleteConfirmId = $id;
        $this->showDeleteModal = true;
    }

    /**
     * Verwijder een beschikbaarheid via stored procedure
     * Controleert of beschikbaarheid verwijderd mag worden
     *
     * @return void
     */
    public function deleteBeschikbaarheid(): void
    {
        if (!$this->deleteConfirmId) {
            return;
        }

        try {
            // Haal de beschikbaarheid op om medewerker_id te krijgen
            $beschikbaarheid = Beschikbaarheid::findOrFail($this->deleteConfirmId);

            // Roep de stored procedure aan via het model
            $result = Beschikbaarheid::deleteViaProcedure($this->deleteConfirmId, $beschikbaarheid->medewerker_id);

            if ($result['success']) {
                // Sluit modal en toon succesmelding
                $this->showDeleteModal = false;
                $this->deleteConfirmId = null;
                session()->flash('success', $result['message']);

                // Dispatch event voor frontend notificatie
                $this->dispatch('beschikbaarheid-deleted');
            } else {
                // Toon foutmelding aan gebruiker
                session()->flash('error', $result['message']);
            }
        } catch (\Exception $e) {
            Log::error('Onverwachte fout bij verwijderen beschikbaarheid', [
                'beschikbaarheid_id' => $this->deleteConfirmId,
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
     * Render de component met gepagineerde beschikbaarheid lijst
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $query = Beschikbaarheid::with(['medewerker.persoon'])
            ->when($this->filterMedewerker !== 'all', function ($q) {
                $q->where('medewerker_id', $this->filterMedewerker);
            })
            ->orderBy('datum_vanaf', 'desc');

        $medewerkers = Medewerker::with('persoon')
            ->where('is_actief', true)
            ->get();

        return view('livewire.beschikbaarheid-beheer', [
            'beschikbaarheden' => $query->paginate(10),
            'medewerkers' => $medewerkers,
        ]);
    }
}
