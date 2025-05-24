<?php
// app/Http/Controllers/MedicalRecordController.php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of medical records.
     */
    public function index(Request $request)
    {
        $query = MedicalRecord::with(['patient']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('patient_number', 'like', "%{$search}%");
            })->orWhere('visit_number', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $records = $query->latest()->paginate(15);

        return view('medical-records.index', compact('records'));
    }

    /**
     * Display the specified medical record.
     */
    public function show(MedicalRecord $record)
    {
        $record->load(['patient', 'prescriptions.medicine']);
        
        return view('medical-records.show', compact('record'));
    }

    // ==================== PERAWAT METHODS ====================

    /**
     * Display list for vitals input (Perawat)
     */
    public function vitalsIndex(Request $request)
    {
        $query = MedicalRecord::with(['patient'])
            ->where('status', 'registered');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('patient_number', 'like', "%{$search}%");
            });
        }

        $records = $query->latest()->paginate(15);

        return view('medical-records.vitals.index', compact('records'));
    }

    /**
     * Show form for vitals input (Perawat)
     */
    public function editVitals(MedicalRecord $record)
    {
        if (!in_array($record->status, ['registered', 'vitals_checked'])) {
            return redirect()
                ->route('vitals.index')
                ->with('warning', '⚠️ Vital signs untuk pasien ini sudah tidak dapat diubah');
        }

        return view('medical-records.vitals.edit', compact('record'));
    }

    /**
     * Update vitals (Perawat)
     */
    public function updateVitals(Request $request, MedicalRecord $record)
    {
        try {
            // Simple validation without custom messages to avoid array unpacking error
            $request->validate([
                'weight' => 'required|numeric|min:1|max:500',
                'blood_pressure' => 'required|string',
                'temperature' => 'nullable|numeric|min:30|max:45',
                'heart_rate' => 'nullable|integer|min:30|max:250',
            ]);

            // Get validated data manually
            $validated = [
                'weight' => $request->input('weight'),
                'blood_pressure' => $request->input('blood_pressure'),
                'temperature' => $request->input('temperature'),
                'heart_rate' => $request->input('heart_rate'),
            ];

            // Validate blood pressure format manually
            if (!preg_match('/^\d{2,3}\/\d{2,3}$/', $validated['blood_pressure'])) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['blood_pressure' => 'Format tekanan darah harus seperti: 120/80']);
            }

            $record->update([
                'weight' => $validated['weight'],
                'blood_pressure' => $validated['blood_pressure'],
                'temperature' => $validated['temperature'],
                'heart_rate' => $validated['heart_rate'],
                'status' => 'vitals_checked',
                'vitals_at' => now()
            ]);

            // Check for abnormal vitals
            $warnings = [];
            
            // Blood pressure check
            $bpParts = explode('/', $validated['blood_pressure']);
            if (count($bpParts) == 2) {
                $systolic = (int)$bpParts[0];
                $diastolic = (int)$bpParts[1];
                
                if ($systolic >= 140 || $diastolic >= 90) {
                    $warnings[] = 'Tekanan darah tinggi';
                } elseif ($systolic < 90 || $diastolic < 60) {
                    $warnings[] = 'Tekanan darah rendah';
                }
            }

            // Temperature check
            if ($validated['temperature'] && $validated['temperature'] >= 37.5) {
                $warnings[] = 'Demam';
            }

            // Heart rate check
            if ($validated['heart_rate']) {
                if ($validated['heart_rate'] > 100) {
                    $warnings[] = 'Detak jantung tinggi';
                } elseif ($validated['heart_rate'] < 60) {
                    $warnings[] = 'Detak jantung rendah';
                }
            }

            $message = '💉 Vital signs berhasil dicatat untuk ' . $record->patient->name;
            $type = 'success';

            if (!empty($warnings)) {
                $message .= ' ⚠️ Perhatian: ' . implode(', ', $warnings);
                $type = 'warning';
            }

            return redirect()
                ->route('vitals.index')
                ->with($type, $message);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Terjadi kesalahan saat menyimpan vital signs: ' . $e->getMessage());
        }
    }

    // ==================== DOKTER METHODS ====================

    /**
     * Display list for diagnosis (Dokter)
     */
    public function diagnosisIndex(Request $request)
    {
        $query = MedicalRecord::with(['patient'])
            ->where('status', 'vitals_checked');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('patient_number', 'like', "%{$search}%");
            });
        }

        $records = $query->latest()->paginate(15);

        return view('medical-records.diagnosis.index', compact('records'));
    }

    /**
     * Show form for diagnosis (Dokter)
     */
    public function editDiagnosis(MedicalRecord $record)
    {
        if (!in_array($record->status, ['vitals_checked', 'diagnosed'])) {
            return redirect()
                ->route('diagnosis.index')
                ->with('warning', '⚠️ Diagnosis untuk pasien ini sudah tidak dapat diubah');
        }

        return view('medical-records.diagnosis.edit', compact('record'));
    }

    /**
     * Update diagnosis (Dokter)
     */
    public function updateDiagnosis(Request $request, MedicalRecord $record)
    {
        try {
            $request->validate([
                'complaints' => 'required|string|max:1000',
                'diagnosis' => 'required|string|max:1000',
                'notes' => 'nullable|string|max:1000',
            ]);

            $record->update([
                'complaints' => $request->input('complaints'),
                'diagnosis' => $request->input('diagnosis'),
                'notes' => $request->input('notes'),
                'status' => 'diagnosed',
                'diagnosed_at' => now()
            ]);

            return redirect()
                ->route('diagnosis.index')
                ->with('success', '🧬 Diagnosis berhasil disimpan untuk ' . $record->patient->name . '! Status: Menunggu resep');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Terjadi kesalahan saat menyimpan diagnosis: ' . $e->getMessage());
        }
    }

    // ==================== APOTEKER METHODS ====================

    /**
     * Display list for prescriptions (Apoteker)
     */
    public function prescriptionIndex(Request $request)
    {
        $query = MedicalRecord::with(['patient'])
            ->where('status', 'diagnosed');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('patient_number', 'like', "%{$search}%");
            });
        }

        $records = $query->latest()->paginate(15);

        return view('medical-records.prescriptions.index', compact('records'));
    }

    /**
     * Show form for prescriptions (Apoteker)
     */
    public function editPrescription(MedicalRecord $record)
    {
        if (!in_array($record->status, ['diagnosed', 'prescribed'])) {
            return redirect()
                ->route('prescriptions.index')
                ->with('warning', '⚠️ Resep untuk pasien ini sudah tidak dapat diubah');
        }

        $medicines = Medicine::where('stock', '>', 0)->orderBy('name')->get();
        $existingPrescriptions = $record->prescriptions()->with('medicine')->get();

        return view('medical-records.prescriptions.edit', compact('record', 'medicines', 'existingPrescriptions'));
    }

    /**
     * Update prescriptions (Apoteker)
     */
    public function updatePrescription(Request $request, MedicalRecord $record)
    {
        try {
            $request->validate([
                'medicines' => 'required|array|min:1',
                'medicines.*.medicine_id' => 'required|exists:medicines,id',
                'medicines.*.dosage' => 'required|string|max:50',
                'medicines.*.instructions' => 'required|string|max:200',
                'medicines.*.quantity' => 'required|integer|min:1|max:1000',
            ]);

            $medicines = $request->input('medicines', []);

            DB::transaction(function () use ($medicines, $record) {
                // FIRST: Restore stock from existing prescriptions before deleting
                $existingPrescriptions = $record->prescriptions()->with('medicine')->get();
                foreach ($existingPrescriptions as $prescription) {
                    // Restore stock
                    $prescription->medicine->increment('stock', $prescription->quantity);
                }
                
                // THEN: Delete existing prescriptions  
                $record->prescriptions()->delete();

                // FINALLY: Create new prescriptions
                $processedMedicines = []; // Track processed medicines to prevent duplicates
                
                foreach ($medicines as $medicineData) {
                    $medicineId = $medicineData['medicine_id'];
                    
                    // Skip if medicine already processed (prevent duplicates)
                    if (in_array($medicineId, $processedMedicines)) {
                        continue;
                    }
                    
                    $medicine = Medicine::find($medicineId);
                    
                    // Check stock availability
                    if ($medicine->stock < $medicineData['quantity']) {
                        throw new \Exception("Stok {$medicine->name} tidak mencukupi. Tersedia: {$medicine->stock}, Diminta: {$medicineData['quantity']}");
                    }

                    // Create prescription
                    Prescription::create([
                        'medical_record_id' => $record->id,
                        'medicine_id' => $medicineId,
                        'dosage' => $medicineData['dosage'],
                        'instructions' => $medicineData['instructions'],
                        'quantity' => $medicineData['quantity'],
                    ]);

                    // Update medicine stock
                    $medicine->decrement('stock', $medicineData['quantity']);
                    
                    // Mark medicine as processed
                    $processedMedicines[] = $medicineId;
                }

                // Update medical record status
                $record->update([
                    'status' => 'completed',
                    'prescribed_at' => now(),
                    'completed_at' => now()
                ]);
            });

            return redirect()
                ->route('medical-records.show', $record)
                ->with('success', '💊 Resep obat berhasil dibuat untuk ' . $record->patient->name . '! Kunjungan selesai.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ ' . $e->getMessage());
        }
    }

    /**
     * Create new medical record for patient
     */
    public function createMedicalRecord(Request $request)
    {
        $patients = Patient::orderBy('name')->get();
        
        return view('medical-records.create', compact('patients'));
    }

    /**
     * Store new medical record
     */
    public function storeMedicalRecord(Request $request)
    {
        try {
            $request->validate([
                'patient_id' => 'required|exists:patients,id'
            ]);

            $patient = Patient::find($request->input('patient_id'));

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
                ->with('success', '🏥 Kunjungan baru berhasil didaftarkan untuk ' . $patient->name . ' dengan nomor: ' . $medicalRecord->visit_number);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Terjadi kesalahan saat membuat kunjungan baru: ' . $e->getMessage());
        }
    }
}