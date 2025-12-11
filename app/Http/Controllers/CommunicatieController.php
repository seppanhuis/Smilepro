<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Communicatie;

class CommunicatieController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Communicatie();
    }

    // Stap 1 – toon lijst van medewerkers voor een patient
    public function index($patientId)
    {
        $medewerkers = $this->model->getMedewerkersVoorPatient($patientId);

        return view('communicatie.index', [
            'medewerkers' => $medewerkers,
            'patientId' => $patientId
        ]);
    }

    // Stap 2 – toon chat met geselecteerde medewerker
    public function gesprek($patientId, $medewerkerId)
    {
        $berichten = $this->model->getBerichtenMetMedewerker($patientId, $medewerkerId);

        return view('communicatie.gesprek', [
            'berichten' => $berichten,
            'patientId' => $patientId,
            'medewerkerId' => $medewerkerId
        ]);
    }
}
