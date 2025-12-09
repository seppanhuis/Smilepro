<?php

namespace App\Http\Controllers;

use App\Models\Acount;
use Illuminate\Http\Request;

class AcountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Test mode: simuleer lege staat met ?test_empty=1
        if ($request->has('test_empty') && $request->get('test_empty') == '1') {
            $accounts = collect();
        } else {
            $accounts = Acount::orderBy('rol_naam', 'asc')->orderBy('gebruikersnaam', 'asc')->get();
        }

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
