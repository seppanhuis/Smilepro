<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Afspraak;
use App\Models\Tijdslot;

class AfspraakWijzigenModal extends Component
{
    public $toonModal = false;
    public $afspraakId;
    public $tijdsloten = [];
    public $geselecteerdTijdslotId;
    public $foutmelding;

    protected $listeners = ['open-wijzigen-modal' => 'laadAfspraak'];

    public function laadAfspraak($event)
    {
        $this->afspraakId = $event['afspraakId'];

        $afspraak = Afspraak::find($this->afspraakId);

        $tijdsloten = Tijdslot::where('datum', $afspraak->datum)
            ->where('is_bezet', false)
            ->get();

        if ($tijdsloten->isEmpty()) {
            $this->foutmelding = "Er zijn geen beschikbare tijdsloten.";
        } else {
            $this->foutmelding = null;
        }

        $this->tijdsloten = $tijdsloten;

        $this->toonModal = true;
    }

    public function wijzigAfspraak()
    {
        if (!$this->geselecteerdTijdslotId) {
            $this->foutmelding = "Selecteer een tijdslot.";
            return;
        }

        $slot = Tijdslot::find($this->geselecteerdTijdslotId);
        $afspraak = Afspraak::find($this->afspraakId);

        $afspraak->update([
            'starttijd' => $slot->starttijd,
            'eindtijd'  => $slot->eindtijd,
        ]);

        $slot->update(['is_bezet' => true]);

        $this->toonModal = false;

        session()->flash('message', 'Afspraak succesvol gewijzigd!');
    }

    public function render()
    {
        return view('livewire.afspraak-wijzigen-modal');
    }
}
