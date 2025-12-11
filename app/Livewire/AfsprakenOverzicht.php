<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Persoon;
use App\Models\Patient;
use App\Models\Afspraak;

class AfsprakenOverzicht extends Component
{
    public $afspraken = [];

    public function mount()
    {
        // Simuleer lege database wanneer test_empty=1 in de URL staat
        if (request()->query('test_empty') == 1) {
            $this->afspraken = collect();
            return;
        }

        $user = Auth::user();

        if (!$user) {
            $this->afspraken = collect();
            return;
        }

        $persoon = Persoon::where('gebruiker_id', $user->id)->first();

        if (!$persoon) {
            $this->afspraken = collect();
            return;
        }

        $patient = Patient::where('persoon_id', $persoon->id)->first();

        if (!$patient) {
            $this->afspraken = collect();
            return;
        }

        $this->afspraken = Afspraak::where('patient_id', $patient->id)
            ->orderBy('datum', 'asc')
            ->orderBy('tijd', 'asc')
            ->get();
    }


    public function render()
    {
        return view('livewire.afspraken-overzicht');
    }
}
