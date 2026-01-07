<?php

namespace App\Http\Controllers;

use App\Models\Afspraak;
use App\Models\Behandeling;
use App\Models\Factuur;
use Illuminate\Http\Request;

/**
 * Controller voor het dashboard
 */
class DashboardController extends Controller
{
    /**
     * Toon het dashboard met statistieken
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Aantal afspraken deze maand
        $aantalAfspraken = Afspraak::whereMonth('datum', date('m'))
            ->whereYear('datum', date('Y'))
            ->where('is_actief', true)
            ->count();

        // Aantal behandelingen deze maand
        $aantalBehandelingen = Behandeling::whereMonth('datum', date('m'))
            ->whereYear('datum', date('Y'))
            ->where('is_actief', true)
            ->count();

        // Omzet deze maand (som van alle facturen)
        $omzetMaand = Factuur::whereMonth('datum', date('m'))
            ->whereYear('datum', date('Y'))
            ->where('is_actief', true)
            ->sum('bedrag');

        // Totale omzet dit jaar
        $omzetJaar = Factuur::whereYear('datum', date('Y'))
            ->where('is_actief', true)
            ->sum('bedrag');

        return view('dashboard', [
            'aantalAfspraken' => $aantalAfspraken,
            'aantalBehandelingen' => $aantalBehandelingen,
            'omzetMaand' => $omzetMaand,
            'omzetJaar' => $omzetJaar,
        ]);
    }
}
