<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect to role-specific dashboard
        switch ($user->role) {
            case 'pendaftaran':
                return redirect()->route('dashboard.pendaftaran');
            case 'dokter':
                return redirect()->route('dashboard.dokter');
            case 'perawat':
                return redirect()->route('dashboard.perawat');
            case 'apoteker':
                return redirect()->route('dashboard.apoteker');
            default:
                return redirect()->route('login');
        }
    }

    public function pendaftaran()
    {
        $stats = [
            'total_patients' => Patient::count(),
            'today_registrations' => MedicalRecord::whereDate('created_at', today())->count(),
            'pending_vitals' => MedicalRecord::where('status', 'registered')->count(),
            'completed_today' => MedicalRecord::whereDate('created_at', today())
                ->where('status', 'completed')->count()
        ];

        $recent_patients = Patient::latest()
            ->take(5)
            ->get();

        $pending_records = MedicalRecord::with('patient')
            ->where('status', 'registered')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.pendaftaran', compact('stats', 'recent_patients', 'pending_records'));
    }

    public function dokter()
    {
        $stats = [
            'pending_diagnosis' => MedicalRecord::where('status', 'vitals_checked')->count(),
            'diagnosed_today' => MedicalRecord::whereDate('diagnosed_at', today())->count(),
            'total_patients_today' => MedicalRecord::whereDate('created_at', today())->count(),
            'completed_diagnosis' => MedicalRecord::whereNotNull('diagnosis')->count()
        ];

        $pending_diagnosis = MedicalRecord::with('patient')
            ->where('status', 'vitals_checked')
            ->latest()
            ->take(10)
            ->get();

        $recent_diagnosis = MedicalRecord::with('patient')
            ->whereNotNull('diagnosis')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.dokter', compact('stats', 'pending_diagnosis', 'recent_diagnosis'));
    }

    public function perawat()
    {
        $stats = [
            'pending_vitals' => MedicalRecord::where('status', 'registered')->count(),
            'completed_vitals_today' => MedicalRecord::whereDate('vitals_at', today())->count(),
            'total_patients_today' => MedicalRecord::whereDate('created_at', today())->count(),
            'waiting_doctor' => MedicalRecord::where('status', 'vitals_checked')->count()
        ];

        $pending_vitals = MedicalRecord::with('patient')
            ->where('status', 'registered')
            ->latest()
            ->take(10)
            ->get();

        $recent_vitals = MedicalRecord::with('patient')
            ->whereNotNull('vitals_at')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.perawat', compact('stats', 'pending_vitals', 'recent_vitals'));
    }

    public function apoteker()
    {
        $stats = [
            'pending_prescriptions' => MedicalRecord::where('status', 'diagnosed')->count(),
            'dispensed_today' => MedicalRecord::whereDate('prescribed_at', today())->count(),
            'low_stock_medicines' => Medicine::where('stock', '<=', 10)->count(),
            'total_medicines' => Medicine::count()
        ];

        $pending_prescriptions = MedicalRecord::with('patient')
            ->where('status', 'diagnosed')
            ->latest()
            ->take(10)
            ->get();

        $low_stock_medicines = Medicine::where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();

        return view('dashboard.apoteker', compact('stats', 'pending_prescriptions', 'low_stock_medicines'));
    }
}