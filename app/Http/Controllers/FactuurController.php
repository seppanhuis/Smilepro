<?php

namespace App\Http\Controllers;

use App\Models\Factuur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller voor het beheren van facturen
 *
 * Deze controller verzorgt het weergeven van facturen in het systeem.
 * Alleen praktijkmanagement heeft toegang tot deze functionaliteit.
 */
class FactuurController extends Controller
{
    /**
     * Toon een overzicht van alle facturen
     *
     * Haalt alle facturen op met bijbehorende patiënt- en behandelingsinformatie,
     * gesorteerd op datum (nieuwste eerst).
     * Ondersteunt test mode met ?test_empty=1 om lege staat te simuleren.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Test mode: simuleer lege staat met ?test_empty=1
        if ($request->has('test_empty') && $request->get('test_empty') == '1') {
            $facturen = collect();
        } else {
            // Haal facturen op via model methode (bevat try-catch)
            $facturen = Factuur::getAllFacturen();
        }

        return view('factuur.index', [
            'facturen' => $facturen
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
    public function show(Factuur $factuur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Factuur $factuur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Factuur $factuur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factuur $factuur)
    {
        //
    }
}
