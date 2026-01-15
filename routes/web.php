<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\http\Controllers\AcountController;
use App\http\Controllers\BeschikbaarheidController;
use App\http\Controllers\MedewerkerController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\CommunicatieController;
use App\Http\Controllers\FactuurController;
use App\Http\Controllers\DashboardController;
use App\Livewire\AfsprakenOverzicht;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    Route::get('/patient', [PatientController::class, 'index'])->name('patient.index');;


Route::middleware(['auth'])->group(function () {

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::get('/accounts', [AcountController::class, 'index'])->middleware('role:praktijkmanagement')->name('accounts.index');

    // ============================
    // MEDEWERKER BEHEER
    // ============================
    Route::get('/medewerker', [MedewerkerController::class, 'index'])
        ->middleware('role:praktijkmanagement')
        ->name('medewerker.index');

    Route::get('/medewerker/create', [MedewerkerController::class, 'create'])
        ->middleware('role:praktijkmanagement')
        ->name('medewerker.create');

    Route::post('/medewerker', [MedewerkerController::class, 'store'])
        ->middleware('role:praktijkmanagement')
        ->name('medewerker.store');

    Route::get('/medewerker/{medewerker}/edit', [MedewerkerController::class, 'edit'])
        ->middleware('role:praktijkmanagement')
        ->name('medewerker.edit');

    Route::get('/medewerker/{medewerker}', function(\App\Models\Medewerker $medewerker) {
        return redirect()->route('medewerker.index');
    })->middleware('role:praktijkmanagement')
        ->name('medewerker.show');

    Route::put('/medewerker/{medewerker}', [MedewerkerController::class, 'update'])
        ->middleware('role:praktijkmanagement')
        ->name('medewerker.update');

    Route::delete('/medewerker/{medewerker}', [MedewerkerController::class, 'destroy'])
        ->middleware('role:praktijkmanagement')
        ->name('medewerker.destroy');

    // ============================
    // BESCHIKBAARHEID BEHEER
    // ============================
    // Beschikbaarheid routes - accessible for all employees
    Route::resource('beschikbaarheid', BeschikbaarheidController::class);
    Route::get('/communicatie/{patientId}', [CommunicatieController::class, 'index'])
        ->name('communicatie.index');

    Route::get('/communicatie/{patientId}/{medewerkerId}', [CommunicatieController::class, 'gesprek'])
        ->name('communicatie.gesprek');
    // ============================
    // AFSPRAKEN OVERZICHT (LIVEWIRE)
    // ============================
    Route::get('/afspraken', AfsprakenOverzicht::class)
        ->name('afspraken.index');

    // ============================
    // FACTUREN OVERZICHT
    // ============================
    Route::get('/facturen', [FactuurController::class, 'index'])
        ->middleware('role:praktijkmanagement')
        ->name('factuur.index');

    Route::get('/facturen/create', [FactuurController::class, 'create'])
        ->middleware('role:praktijkmanagement')
        ->name('factuur.create');

    Route::post('/facturen', [FactuurController::class, 'store'])
        ->middleware('role:praktijkmanagement')
        ->name('factuur.store');

});
