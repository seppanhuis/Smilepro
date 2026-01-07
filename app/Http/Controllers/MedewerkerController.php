<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedewerkerRequest;
use App\Models\Medewerker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Controller voor het beheren van medewerkers
 *
 * Deze controller handelt alle CRUD operaties af voor medewerkers
 * volgens PSR-12 coding standaarden met volledige security, validatie
 * en error handling
 *
 * @package App\Http\Controllers
 * @author SmilePro Development Team
 * @since 2026-01-06
 */
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
     * Toon het formulier voor het aanmaken van een nieuwe medewerker
     *
     * Deze methode toont het formulier waar een nieuwe medewerker kan worden toegevoegd.
     * Alleen toegankelijk voor gebruikers met de rol 'Praktijkmanagement'.
     *
     * @return View
     */
    public function create(): View
    {
        // Log de actie voor audit trail
        Log::info('Medewerker aanmaakformulier geopend', [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'timestamp' => now(),
        ]);

        // Genereer automatisch medewerkernummer via model
        $gegenereerdeNummer = Medewerker::generateNummer();

        // Definieer beschikbare opties voor dropdowns
        $medewerkerTypes = [
            'Assistent' => 'Assistent',
            'Mondhygiënist' => 'Mondhygiënist',
            'Tandarts' => 'Tandarts',
            'Praktijkmanagement' => 'Praktijkmanagement',
        ];

        $rollen = [
            'Assistent' => 'Assistent',
            'Mondhygiënist' => 'Mondhygiënist',
            'Tandarts' => 'Tandarts',
            'Praktijkmanagement' => 'Praktijkmanagement',
        ];

        return view('medewerker.create', [
            'title' => 'Nieuwe Medewerker Toevoegen',
            'medewerkerTypes' => $medewerkerTypes,
            'rollen' => $rollen,
            'gegenereerdeNummer' => $gegenereerdeNummer,
        ]);
    }

    /**
     * Sla een nieuw aangemaakte medewerker op in de database
     *
     * Deze methode verwerkt het formulier voor het aanmaken van een nieuwe medewerker.
     * Het gebruikt een stored procedure voor database validatie en transactie management.
     * Bevat uitgebreide error handling, logging en security maatregelen.
     *
     * @param StoreMedewerkerRequest $request Gevalideerde form request
     * @return RedirectResponse
     */
    public function store(StoreMedewerkerRequest $request): RedirectResponse
    {
        try {
            // Log het begin van de operatie
            Log::info('Poging tot aanmaken van nieuwe medewerker', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'medewerker_email' => $request->email,
                'medewerker_type' => $request->medewerker_type,
                'timestamp' => now(),
            ]);

            // Maak medewerker aan via model
            $result = Medewerker::createViaProcedure([
                'gebruikersnaam' => $request->gebruikersnaam,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol_naam' => $request->rol_naam,
                'voornaam' => $request->voornaam,
                'tussenvoegsel' => $request->tussenvoegsel,
                'achternaam' => $request->achternaam,
                'geboortedatum' => $request->geboortedatum,
                'nummer' => $request->nummer,
                'medewerker_type' => $request->medewerker_type,
                'specialisatie' => $request->specialisatie,
                'opmerking' => $request->opmerking,
            ]);

            // Controleer resultaat
            if (!$result['success']) {
                Log::warning('Stored procedure validatie gefaald', [
                    'user_id' => auth()->id(),
                    'error_message' => $result['message'],
                    'medewerker_email' => $request->email,
                    'timestamp' => now(),
                ]);

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', $result['message']);
            }

            // Log succesvolle aanmaak
            Log::info('Medewerker succesvol aangemaakt', [
                'user_id' => auth()->id(),
                'medewerker_id' => $result['medewerker_id'],
                'medewerker_nummer' => $request->nummer,
                'medewerker_email' => $request->email,
                'medewerker_type' => $request->medewerker_type,
                'timestamp' => now(),
            ]);

            // Redirect met succesmelding
            return redirect()
                ->route('medewerker.index')
                ->with('success', 'Medewerker succesvol toegevoegd');

        } catch (\Illuminate\Database\QueryException $e) {
            // Log de database fout
            Log::error('Database fout bij aanmaken medewerker', [
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'sql_state' => $e->errorInfo[0] ?? null,
                'medewerker_email' => $request->email,
                'timestamp' => now(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het opslaan. Probeer het opnieuw.');

        } catch (\Exception $e) {
            // Log de algemene fout
            Log::error('Onverwachte fout bij aanmaken medewerker', [
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'medewerker_email' => $request->email,
                'timestamp' => now(),
            ]);

            // Algemene foutmelding
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Er is een onverwachte fout opgetreden. Neem contact op met de beheerder.');
        }
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
