<?php

namespace App\Http\Controllers;

use App\Models\Medewerker;
use Illuminate\Http\Request;

class MedewerkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Test mode: simuleer lege staat met ?test_empty=1
        if ($request->has('test_empty') && $request->get('test_empty') == '1') {
            $medewerkers = collect();
        } else {
            // Haal alle users op die een medewerker rol hebben
            $medewerkers = \App\Models\User::whereIn('rol_naam', [
                'Assistent',
                'Tandarts',
                'Mondhygiënist',
                'Praktijkmanagement'
            ])->orderBy('rol_naam', 'asc')->orderBy('gebruikersnaam', 'asc')->get();
        }

        return view('medewerker.index', [
            'title' => 'Medewerkers',
            'medewerkers' => $medewerkers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Medewerker $medewerker)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medewerker $medewerker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medewerker $medewerker)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medewerker $medewerker)
    {
        //
    }
}
