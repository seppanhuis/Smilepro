<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\http\Controllers\AcountController;
use App\http\Controllers\BeschikbaarheidController;
use App\http\Controllers\MedewerkerController;
use App\Http\Controllers\PatientController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
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
    Route::get('/medewerker', [MedewerkerController::class, 'index'])->middleware('role:praktijkmanagement')->name('medewerker.index');

    // Beschikbaarheid routes - accessible for all employees
    Route::resource('beschikbaarheid', BeschikbaarheidController::class);
});
