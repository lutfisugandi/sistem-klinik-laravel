@extends('layouts.app')

@section('title', 'Detail Rekam Medis')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('medical-records.index') }}">Rekam Medis</a></li>
<li class="breadcrumb-item active">{{ $record->visit_number }}</li>
@endsection

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-file-medical text-info mr-2"></i>
            Rekam Medis: {{ $record->visit_number }}
        </h1>
        <p class="text-muted">
            Pasien: <strong>{{ $record->patient->name }}</strong> | 
            {{ $record->created_at->format('d F Y, H:i') }}
        </p>
    </div>
    <div class="col-md-4 text-right">
        <!-- Action buttons based on user role and record status -->
        @if(Auth::user()->isPerawat() && $record->canUpdateVitals())
            <a href="{{ route('vitals.edit', $record) }}" class="btn btn-primary">
                <i class="fas fa-heartbeat mr-1"></i>
                Input Vital Signs
            </a>
        @endif
        
        @if(Auth::user()->isDokter() && $record->canDiagnose())
            <a href="{{ route('diagnosis.edit', $record) }}" class="btn btn-warning">
                <i class="fas fa-stethoscope mr-1"></i>
                Input Diagnosis
            </a>
        @endif
        
        @if(Auth::user()->isApoteker() && $record->canPrescribe())
            <a href="{{ route('prescriptions.edit', $record) }}" class="btn btn-success">
                <i class="fas fa-prescription mr-1"></i>
                Buat Resep
            </a>
        @endif
    </div>
</div>

<!-- Status Progress -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tasks mr-1"></i>
                    Progress Kunjungan
                </h3>
            </div>
            <div class="card-body">
                <div class="progress-steps">
                    <div class="step {{ $record->status == 'registered' ? 'active' : ($record->registered_at ? 'completed' : '') }}">
                        <div class="step-icon bg-primary">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="step-content">
                            <h6>Pendaftaran</h6>
                            <small>{{ $record->registered_at ? $record->registered_at->format('H:i') : 'Belum' }}</small>
                        </div>
                    </div>
                    
                    <div class="step {{ $record->status == 'vitals_checked' ? 'active' : ($record->vitals_at ? 'completed' : '') }}">
                        <div class="step-icon bg-info">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div class="step-content">
                            <h6>Vital Signs</h6>
                            <small>{{ $record->vitals_at ? $record->vitals_at->format('H:i') : 'Belum' }}</small>
                        </div>
                    </div>
                    
                    <div class="step {{ $record->status == 'diagnosed' ? 'active' : ($record->diagnosed_at ? 'completed' : '') }}">
                        <div class="step-icon bg-warning">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <div class="step-content">
                            <h6>Diagnosis</h6>
                            <small>{{ $record->diagnosed_at ? $record->diagnosed_at->format('H:i') : 'Belum' }}</small>
                        </div>
                    </div>
                    
                    <div class="step {{ $record->status == 'prescribed' || $record->status == 'completed' ? 'active' : ($record->prescribed_at ? 'completed' : '') }}">
                        <div class="step-icon bg-success">
                            <i class="fas fa-prescription"></i>
                        </div>
                        <div class="step-content">
                            <h6>Resep</h6>
                            <small>{{ $record->prescribed_at ? $record->prescribed_at->format('H:i') : 'Belum' }}</small>
                        </div>
                    </div>
                    
                    <div class="step {{ $record->status == 'completed' ? 'completed' : '' }}">
                        <div class="step-icon bg-secondary">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="step-content">
                            <h6>Selesai</h6>
                            <small>{{ $record->completed_at ? $record->completed_at->format('H:i') : 'Belum' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Patient Information -->
    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user mr-1"></i>
                    Informasi Pasien
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="patient-avatar mx-auto mb-2" style="width: 80px; height: 80px; background: linear-gradient(135deg, #2563eb, #0d9488); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user fa-2x text-white"></i>
                    </div>
                    <h4 class="mb-0">{{ $record->patient->name }}</h4>
                    <p class="text-muted">{{ $record->patient->patient_number }}</p>
                </div>

                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Umur:</strong></td>
                        <td>{{ $record->patient->age }} tahun</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kelamin:</strong></td>
                        <td>
                            <span class="badge badge-{{ $record->patient->gender == 'L' ? 'primary' : 'pink' }}">
                                {{ $record->patient->gender_display }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>No. Telepon:</strong></td>
                        <td>{{ $record->patient->phone }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat:</strong></td>
                        <td>{{ $record->patient->address ?: 'Tidak ada data' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Visit Info -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar mr-1"></i>
                    Info Kunjungan
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>No. Kunjungan:</strong></td>
                        <td>{{ $record->visit_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal:</strong></td>
                        <td>{{ $record->created_at->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Waktu Daftar:</strong></td>
                        <td>{{ $record->registered_at->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge badge-{{ $record->status_color }}">
                                {{ $record->status_display }}
                            </span>
                        </td>
                    </tr>
                    @if($record->completed_at)
                    <tr>
                        <td><strong>Durasi Total:</strong></td>
                        <td>{{ $record->registered_at->diffForHumans($record->completed_at, true) }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Medical Data -->
    <div class="col-md-8">
        <!-- Vital Signs -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-heartbeat mr-1"></i>
                    Vital Signs
                </h3>
                @if(Auth::user()->isPerawat() && $record->canUpdateVitals())
                <div class="card-tools">
                    <a href="{{ route('vitals.edit', $record) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit mr-1"></i>
                        {{ $record->weight ? 'Edit' : 'Input' }} Vital Signs
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body">
                @if($record->weight)
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-weight"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Berat Badan</span>
                                <span class="info-box-number">{{ $record->weight }} kg</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-tachometer-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tekanan Darah</span>
                                <span class="info-box-number">{{ $record->blood_pressure }}</span>
                            </div>
                        </div>
                    </div>
                    @if($record->temperature)
                    <div class="col-md-3">
                        <div class="info-box bg-danger">
                            <span class="info-box-icon"><i class="fas fa-thermometer-half"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Suhu Tubuh</span>
                                <span class="info-box-number">{{ $record->temperature }}°C</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($record->heart_rate)
                    <div class="col-md-3">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-heartbeat"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Detak Jantung</span>
                                <span class="info-box-number">{{ $record->heart_rate }} bpm</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                @if($record->bmi)
                <div class="alert alert-info">
                    <strong>BMI (Body Mass Index):</strong> {{ $record->bmi }}
                    <span class="ml-2 badge badge-{{ $record->bmi < 18.5 ? 'warning' : ($record->bmi > 24.9 ? 'danger' : 'success') }}">
                        @if($record->bmi < 18.5)
                            Underweight
                        @elseif($record->bmi > 24.9)
                            Overweight
                        @else
                            Normal
                        @endif
                    </span>
                </div>
                @endif
                @else
                <div class="text-center py-3 text-muted">
                    <i class="fas fa-heartbeat fa-2x mb-2"></i>
                    <p>Vital signs belum diinput</p>
                    @if(Auth::user()->isPerawat())
                        <a href="{{ route('vitals.edit', $record) }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>
                            Input Vital Signs
                        </a>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Diagnosis -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-stethoscope mr-1"></i>
                    Pemeriksaan & Diagnosis
                </h3>
                @if(Auth::user()->isDokter() && $record->canDiagnose())
                <div class="card-tools">
                    <a href="{{ route('diagnosis.edit', $record) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit mr-1"></i>
                        {{ $record->diagnosis ? 'Edit' : 'Input' }} Diagnosis
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body">
                @if($record->complaints || $record->diagnosis)
                @if($record->complaints)
                <div class="mb-3">
                    <h6><i class="fas fa-comment mr-1"></i> Keluhan Pasien:</h6>
                    <p class="text-muted">{{ $record->complaints }}</p>
                </div>
                @endif

                @if($record->diagnosis)
                <div class="mb-3">
                    <h6><i class="fas fa-diagnoses mr-1"></i> Diagnosis:</h6>
                    <p><strong>{{ $record->diagnosis }}</strong></p>
                </div>
                @endif

                @if($record->notes)
                <div class="mb-3">
                    <h6><i class="fas fa-sticky-note mr-1"></i> Catatan Dokter:</h6>
                    <p class="text-muted">{{ $record->notes }}</p>
                </div>
                @endif
                @else
                <div class="text-center py-3 text-muted">
                    <i class="fas fa-stethoscope fa-2x mb-2"></i>
                    <p>Pemeriksaan dan diagnosis belum dilakukan</p>
                    @if(Auth::user()->isDokter() && $record->vitals_at)
                        <a href="{{ route('diagnosis.edit', $record) }}" class="btn btn-warning">
                            <i class="fas fa-plus mr-1"></i>
                            Input Diagnosis
                        </a>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Prescriptions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-prescription mr-1"></i>
                    Resep Obat
                </h3>
                @if(Auth::user()->isApoteker() && $record->canPrescribe())
                <div class="card-tools">
                    <a href="{{ route('prescriptions.edit', $record) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-edit mr-1"></i>
                        {{ $record->prescriptions->count() > 0 ? 'Edit' : 'Buat' }} Resep
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body">
                @if($record->prescriptions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Obat</th>
                                <th>Dosis</th>
                                <th>Jumlah</th>
                                <th>Instruksi</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalPrice = 0; @endphp
                            @foreach($record->prescriptions as $prescription)
                            <tr>
                                <td>
                                    <strong>{{ $prescription->medicine->name }}</strong><br>
                                    <small class="text-muted">{{ ucfirst($prescription->medicine->type) }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $prescription->dosage }}</span>
                                </td>
                                <td>{{ $prescription->quantity }} {{ $prescription->medicine->unit }}</td>
                                <td>
                                    <small>{{ $prescription->instructions }}</small>
                                </td>
                                <td>{{ $prescription->formatted_total_price }}</td>
                            </tr>
                            @php $totalPrice += $prescription->total_price; @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4">Total Biaya Obat:</th>
                                <th>Rp {{ number_format($totalPrice, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-3 text-muted">
                    <i class="fas fa-prescription fa-2x mb-2"></i>
                    <p>Resep obat belum dibuat</p>
                    @if(Auth::user()->isApoteker() && $record->diagnosis)
                        <a href="{{ route('prescriptions.edit', $record) }}" class="btn btn-success">
                            <i class="fas fa-plus mr-1"></i>
                            Buat Resep
                        </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mt-3">
    <div class="col-md-12 text-center">
        <a href="{{ route('medical-records.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali ke Daftar
        </a>
        <a href="{{ route('patients.show', $record->patient) }}" class="btn btn-info">
            <i class="fas fa-user mr-1"></i>
            Lihat Profile Pasien
        </a>
        @if($record->status == 'completed')
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print mr-1"></i>
            Cetak Rekam Medis
        </button>
        @endif
    </div>
</div>

<style>
.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
    z-index: 1;
}

.step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 30px;
    left: 50%;
    width: 100%;
    height: 2px;
    background: #dee2e6;
    z-index: -1;
}

.step.completed:not(:last-child)::after {
    background: #28a745;
}

.step-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    margin-bottom: 10px;
}

.step:not(.active):not(.completed) .step-icon {
    background: #6c757d;
}

.step.completed .step-icon {
    background: #28a745;
}

.step-content {
    text-align: center;
}

.step-content h6 {
    margin: 0;
    font-weight: bold;
    font-size: 0.9rem;
}

.step-content small {
    color: #6c757d;
}

@media print {
    .btn, .card-tools, .breadcrumb {
        display: none !important;
    }
}
</style>
@endsection