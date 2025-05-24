<?php
// routes/web.php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// Manual Auth Routes (simple)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Dashboard routes (role-specific)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Role-specific dashboard routes  
    Route::get('/dashboard/pendaftaran', [DashboardController::class, 'pendaftaran'])
        ->name('dashboard.pendaftaran');
    Route::get('/dashboard/dokter', [DashboardController::class, 'dokter'])
        ->name('dashboard.dokter');
    Route::get('/dashboard/perawat', [DashboardController::class, 'perawat'])
        ->name('dashboard.perawat');
    Route::get('/dashboard/apoteker', [DashboardController::class, 'apoteker'])
        ->name('dashboard.apoteker');

    // Pendaftaran routes (remove middleware temporarily)
    Route::resource('patients', PatientController::class);

    Route::get('patients/{patient}/medical-record', [PatientController::class, 'createMedicalRecord'])
        ->name('patients.medical-record');

    // Jadi langsung ke POST:
    // Route::get('patients/{patient}/medical-record', function($patient) {
    //     return redirect()->route('patients.store-medical-record', $patient);
    // });    


    Route::post('patients/{patient}/medical-record', [PatientController::class, 'storeMedicalRecord'])
        ->name('patients.store-medical-record');

    // Perawat routes (remove middleware temporarily) 
    Route::get('vitals', [MedicalRecordController::class, 'vitalsIndex'])->name('vitals.index');
    Route::get('vitals/{record}', [MedicalRecordController::class, 'editVitals'])->name('vitals.edit');
    Route::put('vitals/{record}', [MedicalRecordController::class, 'updateVitals'])->name('vitals.update');

    // Dokter routes (remove middleware temporarily)
    Route::get('diagnosis', [MedicalRecordController::class, 'diagnosisIndex'])->name('diagnosis.index');
    Route::get('diagnosis/{record}', [MedicalRecordController::class, 'editDiagnosis'])->name('diagnosis.edit');
    Route::put('diagnosis/{record}', [MedicalRecordController::class, 'updateDiagnosis'])->name('diagnosis.update');

    // Apoteker routes (remove middleware temporarily)
    Route::resource('medicines', MedicineController::class);
    Route::get('prescriptions', [MedicalRecordController::class, 'prescriptionIndex'])->name('prescriptions.index');
    Route::get('prescriptions/{record}', [MedicalRecordController::class, 'editPrescription'])->name('prescriptions.edit');
    Route::put('prescriptions/{record}', [MedicalRecordController::class, 'updatePrescription'])->name('prescriptions.update');

    // Shared routes (semua role bisa akses untuk view)
    Route::get('medical-records', [MedicalRecordController::class, 'index'])->name('medical-records.index');
    Route::get('medical-records/{record}', [MedicalRecordController::class, 'show'])->name('medical-records.show');
});