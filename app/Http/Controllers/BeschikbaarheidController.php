<?php

namespace App\Http\Controllers;

use App\Models\Beschikbaarheid;
use Illuminate\Http\Request;

class BeschikbaarheidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('beschikbaarheid.index', [
            'title' => 'Beschikbaarheid'
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
    public function show(Beschikbaarheid $beschikbaarheid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Beschikbaarheid $beschikbaarheid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Beschikbaarheid $beschikbaarheid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Beschikbaarheid $beschikbaarheid)
    {
        //
    }
}
