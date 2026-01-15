<?php

namespace App\Http\Controllers;

use App\Models\Beschikbaarheid;
use App\Models\Medewerker;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

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
     * Toon het formulier voor het wijzigen van een bestaande beschikbaarheid
     *
     * Deze methode toont het formulier waar een bestaande beschikbaarheid kan worden gewijzigd.
     * Alleen toegankelijk voor gebruikers met de rol 'Praktijkmanagement' of de eigenaar.
     *
     * @param Beschikbaarheid $beschikbaarheid De beschikbaarheid die gewijzigd moet worden
     * @return View
     */
    public function edit(Beschikbaarheid $beschikbaarheid): View
    {
        $user = auth()->user();

        // Check if user can edit this beschikbaarheid
        if ($user->rol_naam !== 'Praktijkmanagement' && $beschikbaarheid->user_id != $user->id) {
            abort(403, 'Je mag alleen je eigen beschikbaarheid bewerken.');
        }

        // Log de actie voor audit trail
        Log::info('Beschikbaarheid wijzigformulier geopend', [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'beschikbaarheid_id' => $beschikbaarheid->id,
            'timestamp' => now(),
        ]);

        // Laad relaties
        $beschikbaarheid->load(['medewerker.persoon', 'user']);

        if ($user->rol_naam === 'Praktijkmanagement') {
            $medewerkers = Medewerker::with('persoon')
                ->where('is_actief', true)
                ->get();
        } else {
            // Voor niet-admins, toon alleen hun eigen medewerker record
            $medewerkers = Medewerker::with('persoon')
                ->where('persoon_id', $user->persoon->id)
                ->get();
        }

        return view('beschikbaarheid.edit', [
            'title' => 'Beschikbaarheid Wijzigen',
            'beschikbaarheid' => $beschikbaarheid,
            'medewerkers' => $medewerkers,
        ]);
    }

    /**
     * Update een bestaande beschikbaarheid in de database
     *
     * Deze methode verwerkt het formulier voor het wijzigen van beschikbaarheid.
     * Het gebruikt een stored procedure voor database validatie en transactie management.
     * Bevat uitgebreide error handling, logging en security maatregelen.
     *
     * @param Request $request De HTTP request met formulier data
     * @param Beschikbaarheid $beschikbaarheid De beschikbaarheid die gewijzigd moet worden
     * @return RedirectResponse
     */
    public function update(Request $request, Beschikbaarheid $beschikbaarheid): RedirectResponse
    {
        $user = auth()->user();

        // Check if user can update this beschikbaarheid
        if ($user->rol_naam !== 'Praktijkmanagement' && $beschikbaarheid->user_id != $user->id) {
            abort(403, 'Je mag alleen je eigen beschikbaarheid bewerken.');
        }

        try {
            // Validatie
            $validated = $request->validate([
                'medewerker_id' => 'required|exists:medewerkers,id',
                'datum_vanaf' => 'required|date|after_or_equal:today',
                'datum_tot_met' => 'required|date|after_or_equal:datum_vanaf',
                'tijd_vanaf' => 'required|date_format:H:i',
                'tijd_tot_met' => 'required|date_format:H:i',
                'status' => 'required|in:Beschikbaar,Niet beschikbaar,Vakantie',
                'is_actief' => 'nullable|boolean',
                'opmerking' => 'nullable|string|max:1000',
            ], [
                'medewerker_id.required' => 'Medewerker is verplicht',
                'medewerker_id.exists' => 'Geselecteerde medewerker bestaat niet',
                'datum_vanaf.required' => 'Start datum is verplicht',
                'datum_vanaf.after_or_equal' => 'Start datum moet vandaag of in de toekomst zijn',
                'datum_tot_met.required' => 'Eind datum is verplicht',
                'datum_tot_met.after_or_equal' => 'Eind datum moet na of gelijk aan start datum zijn',
                'tijd_vanaf.required' => 'Start tijd is verplicht',
                'tijd_vanaf.date_format' => 'Ongeldige tijd format (gebruik HH:MM)',
                'tijd_tot_met.required' => 'Eind tijd is verplicht',
                'tijd_tot_met.date_format' => 'Ongeldige tijd format (gebruik HH:MM)',
                'status.required' => 'Status is verplicht',
                'status.in' => 'Ongeldige status',
            ]);

            // Log het begin van de operatie
            Log::info('Poging tot wijzigen van beschikbaarheid', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'beschikbaarheid_id' => $beschikbaarheid->id,
                'medewerker_id' => $validated['medewerker_id'],
                'timestamp' => now(),
            ]);

            // Update beschikbaarheid via stored procedure
            $result = Beschikbaarheid::updateViaProcedure($beschikbaarheid->id, [
                'medewerker_id' => $validated['medewerker_id'],
                'datum_vanaf' => $validated['datum_vanaf'],
                'datum_tot_met' => $validated['datum_tot_met'],
                'tijd_vanaf' => $validated['tijd_vanaf'],
                'tijd_tot_met' => $validated['tijd_tot_met'],
                'status' => $validated['status'],
                'is_actief' => $request->has('is_actief') ? true : false,
                'opmerking' => $validated['opmerking'] ?? null,
            ]);

            // Controleer resultaat
            if (!$result['success']) {
                Log::warning('Stored procedure validatie gefaald bij wijzigen beschikbaarheid', [
                    'user_id' => auth()->id(),
                    'beschikbaarheid_id' => $beschikbaarheid->id,
                    'error_message' => $result['message'],
                    'timestamp' => now(),
                ]);

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', $result['message']);
            }

            // Log succesvolle wijziging
            Log::info('Beschikbaarheid succesvol gewijzigd', [
                'user_id' => auth()->id(),
                'beschikbaarheid_id' => $beschikbaarheid->id,
                'timestamp' => now(),
            ]);

            return redirect()
                ->route('beschikbaarheid.index')
                ->with('success', 'Beschikbaarheid succesvol gewijzigd');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (\Illuminate\Database\QueryException $e) {
            // Log de database fout
            Log::error('Database fout bij wijzigen beschikbaarheid', [
                'user_id' => auth()->id(),
                'beschikbaarheid_id' => $beschikbaarheid->id,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'timestamp' => now(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het opslaan. Probeer het opnieuw.');

        } catch (\Exception $e) {
            // Log de algemene fout
            Log::error('Onverwachte fout bij wijzigen beschikbaarheid', [
                'user_id' => auth()->id(),
                'beschikbaarheid_id' => $beschikbaarheid->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'timestamp' => now(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Er is een onverwachte fout opgetreden. Neem contact op met de beheerder.');
        }
    }

    /**
     * Verwijder een beschikbaarheid uit de database
     *
     * Deze methode verwijdert een beschikbaarheid via een stored procedure.
     * De stored procedure controleert of de beschikbaarheid verwijderd mag worden
     * (bijv. of het bij de juiste medewerker hoort).
     * Bevat uitgebreide error handling, logging en security maatregelen.
     *
     * @param Beschikbaarheid $beschikbaarheid De beschikbaarheid die verwijderd moet worden
     * @return RedirectResponse
     */
    public function destroy(Beschikbaarheid $beschikbaarheid): RedirectResponse
    {
        $user = auth()->user();

        // Check if user can delete this beschikbaarheid
        if ($user->rol_naam !== 'Praktijkmanagement' && $beschikbaarheid->user_id != $user->id) {
            abort(403, 'Je mag alleen je eigen beschikbaarheid verwijderen.');
        }

        try {
            // Log het begin van de operatie
            Log::info('Poging tot verwijderen van beschikbaarheid', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'beschikbaarheid_id' => $beschikbaarheid->id,
                'medewerker_id' => $beschikbaarheid->medewerker_id,
                'timestamp' => now(),
            ]);

            // Verwijder beschikbaarheid via stored procedure
            $result = Beschikbaarheid::deleteViaProcedure(
                $beschikbaarheid->id,
                $beschikbaarheid->medewerker_id
            );

            // Controleer resultaat
            if (!$result['success']) {
                Log::warning('Stored procedure validatie gefaald bij verwijderen beschikbaarheid', [
                    'user_id' => auth()->id(),
                    'beschikbaarheid_id' => $beschikbaarheid->id,
                    'error_message' => $result['message'],
                    'timestamp' => now(),
                ]);

                return redirect()
                    ->back()
                    ->with('error', $result['message']);
            }

            // Log succesvolle verwijdering
            Log::info('Beschikbaarheid succesvol verwijderd', [
                'user_id' => auth()->id(),
                'beschikbaarheid_id' => $beschikbaarheid->id,
                'timestamp' => now(),
            ]);

            return redirect()
                ->route('beschikbaarheid.index')
                ->with('success', 'Beschikbaarheid succesvol verwijderd');

        } catch (\Illuminate\Database\QueryException $e) {
            // Log de database fout
            Log::error('Database fout bij verwijderen beschikbaarheid', [
                'user_id' => auth()->id(),
                'beschikbaarheid_id' => $beschikbaarheid->id,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'timestamp' => now(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Er is een fout opgetreden bij het verwijderen. Probeer het opnieuw.');

        } catch (\Exception $e) {
            // Log de algemene fout
            Log::error('Onverwachte fout bij verwijderen beschikbaarheid', [
                'user_id' => auth()->id(),
                'beschikbaarheid_id' => $beschikbaarheid->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'timestamp' => now(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Er is een onverwachte fout opgetreden. Neem contact op met de beheerder.');
        }
    }
}
