<?php
// app/Http/Controllers/PatientController.php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index(Request $request)
    {
        $query = Patient::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('patient_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $patients = $query->latest()->paginate(15);

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created patient.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'birth_date' => 'required|date|before:today',
                'gender' => 'required|in:L,P',
                'phone' => 'required|string|max:15',
                'address' => 'nullable|string|max:500',
            ], [
                'name.required' => 'Nama pasien wajib diisi',
                'birth_date.required' => 'Tanggal lahir wajib diisi',
                'birth_date.before' => 'Tanggal lahir harus sebelum hari ini',
                'gender.required' => 'Jenis kelamin wajib dipilih',
                'phone.required' => 'Nomor telepon wajib diisi',
            ]);

            // Check if phone already exists
            $existingPatient = Patient::where('phone', $validated['phone'])->first();
            if ($existingPatient) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('warning', 'Nomor telepon sudah terdaftar atas nama: ' . $existingPatient->name);
            }

            $patient = Patient::create($validated);

            return redirect()
                ->route('patients.show', $patient)
                ->with('success', '🎉 Pasien berhasil didaftarkan! Nomor pasien: ' . $patient->patient_number);
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Terjadi kesalahan saat menyimpan data pasien. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient)
    {
        $patient->load('medicalRecords.prescriptions.medicine');
        
        $medicalRecords = $patient->medicalRecords()
            ->latest()
            ->paginate(10);

        return view('patients.show', compact('patient', 'medicalRecords'));
    }

    /**
     * Show the form for editing the patient.
     */
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient.
     */
    public function update(Request $request, Patient $patient)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'birth_date' => 'required|date|before:today',
                'gender' => 'required|in:L,P',
                'phone' => 'required|string|max:15',
                'address' => 'nullable|string|max:500',
            ], [
                'name.required' => 'Nama pasien wajib diisi',
                'birth_date.required' => 'Tanggal lahir wajib diisi',
                'birth_date.before' => 'Tanggal lahir harus sebelum hari ini',
                'gender.required' => 'Jenis kelamin wajib dipilih',
                'phone.required' => 'Nomor telepon wajib diisi',
            ]);

            // Check if phone already exists (exclude current patient)
            $existingPatient = Patient::where('phone', $validated['phone'])
                ->where('id', '!=', $patient->id)
                ->first();
                
            if ($existingPatient) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('warning', '📞 Nomor telepon sudah digunakan oleh pasien: ' . $existingPatient->name);
            }

            $patient->update($validated);

            return redirect()
                ->route('patients.show', $patient)
                ->with('success', '✅ Data pasien berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified patient.
     */
    public function destroy(Patient $patient)
    {
        try {
            // Check if patient has medical records
            if ($patient->medicalRecords()->count() > 0) {
                return redirect()
                    ->route('patients.index')
                    ->with('error', '🔒 Pasien tidak dapat dihapus karena memiliki ' . $patient->medicalRecords()->count() . ' rekam medis');
            }

            $patientName = $patient->name;
            $patient->delete();

            return redirect()
                ->route('patients.index')
                ->with('success', '🗑️ Data pasien "' . $patientName . '" berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('patients.index')
                ->with('error', '❌ Terjadi kesalahan saat menghapus data pasien.');
        }
    }

    /**
     * Show form to create medical record for patient.
     */
    public function createMedicalRecord(Patient $patient)
    {
        return view('patients.create-medical-record', compact('patient'));
    }

    /**
     * Store medical record for patient.
     */
    public function storeMedicalRecord(Request $request, Patient $patient)
    {
        // Check if patient already has active medical record today
        $existingRecord = $patient->medicalRecords()
            ->whereDate('created_at', today())
            ->where('status', '!=', 'completed')
            ->first();

        if ($existingRecord) {
            return redirect()
                ->route('medical-records.show', $existingRecord)
                ->with('info', 'ℹ️ Pasien sudah memiliki kunjungan aktif hari ini');
        }

        $medicalRecord = MedicalRecord::create([
            'patient_id' => $patient->id,
            'status' => 'registered',
        ]);

        return redirect()
            ->route('medical-records.show', $medicalRecord)
            ->with('success', 'Kunjungan baru berhasil didaftarkan dengan nomor: ' . $medicalRecord->visit_number);
    }
}