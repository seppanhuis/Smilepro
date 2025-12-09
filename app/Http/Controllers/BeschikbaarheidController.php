<?php

namespace App\Http\Controllers;

use App\Models\Beschikbaarheid;
use App\Models\User;
use Illuminate\Http\Request;

class BeschikbaarheidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Test mode: simuleer lege staat met ?test_empty=1
        if ($request->has('test_empty') && $request->get('test_empty') == '1') {
            $beschikbaarheden = collect();
        } else {
            // Iedereen ziet alle beschikbaarheden
            $beschikbaarheden = Beschikbaarheid::with('user')
                ->orderBy('datum_vanaf', 'desc')
                ->get();
        }

        $medewerkers = User::whereIn('rol_naam', ['Assistent', 'Tandarts', 'Mondhygiënist', 'Praktijkmanagement'])
            ->orderBy('gebruikersnaam')
            ->get();

        return view('beschikbaarheid.index', [
            'title' => 'Beschikbaarheid',
            'beschikbaarheden' => $beschikbaarheden,
            'medewerkers' => $medewerkers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        if ($user->rol_naam === 'Praktijkmanagement') {
            $medewerkers = User::whereIn('rol_naam', ['Assistent', 'Tandarts', 'Mondhygiënist', 'Praktijkmanagement'])
                ->orderBy('gebruikersnaam')
                ->get();
        } else {
            $medewerkers = collect([$user]);
        }

        return view('beschikbaarheid.create', compact('medewerkers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'datum_vanaf' => 'required|date',
            'datum_tot_met' => 'required|date|after_or_equal:datum_vanaf',
            'tijd_vanaf' => 'required',
            'tijd_tot_met' => 'required',
            'status' => 'required|in:Aanwezig,Afwezig,Verlof,Ziek',
            'opmerking' => 'nullable|string',
        ]);

        // Check if user can create for this user_id
        if ($user->rol_naam !== 'Praktijkmanagement' && $validated['user_id'] != $user->id) {
            abort(403, 'Je mag alleen je eigen beschikbaarheid toevoegen.');
        }

        Beschikbaarheid::create($validated);

        return redirect()->route('beschikbaarheid.index')
            ->with('success', 'Beschikbaarheid succesvol toegevoegd.');
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
        $user = auth()->user();

        // Check if user can edit this beschikbaarheid
        if ($user->rol_naam !== 'Praktijkmanagement' && $beschikbaarheid->user_id != $user->id) {
            abort(403, 'Je mag alleen je eigen beschikbaarheid bewerken.');
        }

        if ($user->rol_naam === 'Praktijkmanagement') {
            $medewerkers = User::whereIn('rol_naam', ['Assistent', 'Tandarts', 'Mondhygiënist', 'Praktijkmanagement'])
                ->orderBy('gebruikersnaam')
                ->get();
        } else {
            $medewerkers = collect([$user]);
        }

        return view('beschikbaarheid.edit', compact('beschikbaarheid', 'medewerkers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Beschikbaarheid $beschikbaarheid)
    {
        $user = auth()->user();

        // Check if user can update this beschikbaarheid
        if ($user->rol_naam !== 'Praktijkmanagement' && $beschikbaarheid->user_id != $user->id) {
            abort(403, 'Je mag alleen je eigen beschikbaarheid bewerken.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'datum_vanaf' => 'required|date',
            'datum_tot_met' => 'required|date|after_or_equal:datum_vanaf',
            'tijd_vanaf' => 'required',
            'tijd_tot_met' => 'required',
            'status' => 'required|in:Aanwezig,Afwezig,Verlof,Ziek',
            'opmerking' => 'nullable|string',
        ]);

        $beschikbaarheid->update($validated);

        return redirect()->route('beschikbaarheid.index')
            ->with('success', 'Beschikbaarheid succesvol bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Beschikbaarheid $beschikbaarheid)
    {
        $user = auth()->user();

        // Check if user can delete this beschikbaarheid
        if ($user->rol_naam !== 'Praktijkmanagement' && $beschikbaarheid->user_id != $user->id) {
            abort(403, 'Je mag alleen je eigen beschikbaarheid verwijderen.');
        }

        $beschikbaarheid->delete();

        return redirect()->route('beschikbaarheid.index')
            ->with('success', 'Beschikbaarheid succesvol verwijderd.');
    }
}
