<?php

namespace App\Http\Controllers;

use App\Models\Acount;
use Illuminate\Http\Request;

class AcountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Acount::orderBy('created_at', 'desc')->get();

        return view('accounts.index', [
            'title' => 'Accounts',
            'accounts' => $accounts
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
    public function show(Acount $acount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Acount $acount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Acount $acount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acount $acount)
    {
        //
    }
}
