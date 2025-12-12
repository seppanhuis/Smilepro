<?php

namespace App\Http\Controllers;

use App\Models\PatientModel;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    private $PatientModel;

    public function __construct()
    {
        $this->PatientModel = new PatientModel();
    }

    public function index(Request $request)
    {
        // Test mode: simuleer lege staat met ?test_empty=1
        if ($request->has('test_empty') && $request->get('test_empty') == '1') {
            $patients = collect();
        } else {
            $patients = $this->PatientModel->getPatientData();
        }

        return view('patient.index', [
            'title' => 'Overzicht patiënten',
            'patients' => $patients
        ]);
    }

    // Other resource methods can stay empty for now
    public function create() {}
    public function store(Request $request) {}
    public function show(PatientModel $patientModel) {}
    public function edit(PatientModel $patientModel) {}
    public function update(Request $request, PatientModel $patientModel) {}
    public function destroy(PatientModel $patientModel) {}
}
