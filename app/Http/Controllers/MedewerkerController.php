<?php

namespace App\Http\Controllers;

use App\Models\Medewerker;
use Illuminate\Http\Request;

class MedewerkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('medewerker.index', [
            'title' => 'Medewerkers'
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
