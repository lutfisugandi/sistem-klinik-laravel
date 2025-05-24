@extends('layouts.app')

@section('title', 'Input Vital Signs')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('vitals.index') }}">Input Vital Signs</a></li>
<li class="breadcrumb-item active">{{ $record->patient->name }}</li>
@endsection

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-md-12">
        <h1 class="h3 mb-0">
            <i class="fas fa-heartbeat text-danger mr-2"></i>
            Input Vital Signs
        </h1>
        <p class="text-muted">
            Pasien: <strong>{{ $record->patient->name }}</strong> | 
            No. Kunjungan: <strong>{{ $record->visit_number }}</strong> | 
            {{ $record->registered_at->format('d F Y, H:i') }}
        </p>
    </div>
</div>

<!-- Patient Info & Form -->
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
                    <div class="avatar-circle mx-auto mb-2" style="width: 80px; height: 80px; background: linear-gradient(135deg, #dc3545, #fd7e14); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
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
                        <td><strong>Waktu Daftar:</strong></td>
                        <td>{{ $record->registered_at->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Waiting Time:</strong></td>
                        <td>
                            @php
                                $waitingTime = $record->registered_at->diffInMinutes(now());
                            @endphp
                            <span class="badge badge-{{ $waitingTime > 60 ? 'danger' : ($waitingTime > 30 ? 'warning' : 'info') }}">
                                {{ $waitingTime }} menit
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Previous Vitals -->
        @php
            $previousRecord = $record->patient->medicalRecords()
                ->where('id', '!=', $record->id)
                ->whereNotNull('weight')
                ->latest()
                ->first();
        @endphp
        @if($previousRecord)
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Vital Signs Terakhir
                </h3>
            </div>
            <div class="card-body">
                <small class="text-muted">{{ $previousRecord->vitals_at->format('d/m/Y') }}</small>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>BB:</td>
                        <td><strong>{{ $previousRecord->weight }} kg</strong></td>
                    </tr>
                    <tr>
                        <td>TD:</td>
                        <td><strong>{{ $previousRecord->blood_pressure }}</strong></td>
                    </tr>
                    @if($previousRecord->temperature)
                    <tr>
                        <td>Suhu:</td>
                        <td><strong>{{ $previousRecord->temperature }}°C</strong></td>
                    </tr>
                    @endif
                    @if($previousRecord->heart_rate)
                    <tr>
                        <td>Nadi:</td>
                        <td><strong>{{ $previousRecord->heart_rate }} bpm</strong></td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Vital Signs Form -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-heartbeat mr-1"></i>
                    Form Input Vital Signs
                </h3>
            </div>
            <form action="{{ route('vitals.update', $record) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Berat Badan -->
                    <div class="form-group">
                        <label for="weight" class="required">Berat Badan (kg)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-weight"></i></span>
                            </div>
                            <input type="number" 
                                   class="form-control form-control-lg @error('weight') is-invalid @enderror" 
                                   id="weight" 
                                   name="weight" 
                                   value="{{ old('weight', $record->weight) }}"
                                   step="0.1"
                                   min="1"
                                   max="500"
                                   placeholder="Contoh: 65.5"
                                   required>
                            <div class="input-group-append">
                                <span class="input-group-text">kg</span>
                            </div>
                        </div>
                        @error('weight')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tekanan Darah -->
                    <div class="form-group">
                        <label for="blood_pressure" class="required">Tekanan Darah (mmHg)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                            </div>
                            <input type="text" 
                                   class="form-control form-control-lg @error('blood_pressure') is-invalid @enderror" 
                                   id="blood_pressure" 
                                   name="blood_pressure" 
                                   value="{{ old('blood_pressure', $record->blood_pressure) }}"
                                   placeholder="Contoh: 120/80"
                                   pattern="[0-9]{2,3}/[0-9]{2,3}"
                                   required>
                            <div class="input-group-append">
                                <span class="input-group-text">mmHg</span>
                            </div>
                        </div>
                        @error('blood_pressure')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Format: sistole/diastole (contoh: 120/80)</small>
                    </div>

                    <!-- Suhu Tubuh -->
                    <div class="form-group">
                        <label for="temperature">Suhu Tubuh (°C)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-thermometer-half"></i></span>
                            </div>
                            <input type="number" 
                                   class="form-control form-control-lg @error('temperature') is-invalid @enderror" 
                                   id="temperature" 
                                   name="temperature" 
                                   value="{{ old('temperature', $record->temperature) }}"
                                   step="0.1"
                                   min="30"
                                   max="45"
                                   placeholder="Contoh: 36.5">
                            <div class="input-group-append">
                                <span class="input-group-text">°C</span>
                            </div>
                        </div>
                        @error('temperature')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Opsional - Normal: 36.1 - 37.2°C</small>
                    </div>

                    <!-- Detak Jantung -->
                    <div class="form-group">
                        <label for="heart_rate">Detak Jantung (bpm)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-heartbeat"></i></span>
                            </div>
                            <input type="number" 
                                   class="form-control form-control-lg @error('heart_rate') is-invalid @enderror" 
                                   id="heart_rate" 
                                   name="heart_rate" 
                                   value="{{ old('heart_rate', $record->heart_rate) }}"
                                   min="30"
                                   max="250"
                                   placeholder="Contoh: 72">
                            <div class="input-group-append">
                                <span class="input-group-text">bpm</span>
                            </div>
                        </div>
                        @error('heart_rate')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Opsional - Normal: 60-100 bpm</small>
                    </div>

                    <!-- BMI Calculator (Auto-calculate) -->
                    <div class="alert alert-info" id="bmi-info" style="display: none;">
                        <strong>BMI (Body Mass Index):</strong> <span id="bmi-value"></span>
                        <span id="bmi-status" class="ml-2"></span>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('vitals.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="reset" class="btn btn-warning">
                                <i class="fas fa-undo mr-1"></i>
                                Reset
                            </button>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save mr-1"></i>
                                Simpan Vital Signs
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reference Values -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Nilai Referensi Normal
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-weight"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">BMI Normal</span>
                                <span class="info-box-number">18.5 - 24.9</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-tachometer-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tekanan Darah</span>
                                <span class="info-box-number">90/60 - 140/90</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-thermometer-half"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Suhu Normal</span>
                                <span class="info-box-number">36.1 - 37.2°C</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-heartbeat"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Nadi Normal</span>
                                <span class="info-box-number">60 - 100 bpm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.required:after {
    content: ' *';
    color: red;
}

.form-control-lg {
    font-size: 1.2rem;
    font-weight: bold;
}
</style>

@push('scripts')
<script>
// BMI Calculator
function calculateBMI() {
    const weight = parseFloat($('#weight').val());
    const patientAge = {{ $record->patient->age }};
    const patientGender = '{{ $record->patient->gender }}';
    
    if (weight && weight > 0) {
        // Estimasi tinggi berdasarkan gender dan umur (simplified)
        let estimatedHeight;
        if (patientGender === 'L') {
            estimatedHeight = patientAge < 18 ? 1.6 : 1.7; // meter
        } else {
            estimatedHeight = patientAge < 18 ? 1.5 : 1.6; // meter
        }
        
        const bmi = weight / (estimatedHeight * estimatedHeight);
        const bmiRounded = Math.round(bmi * 10) / 10;
        
        let status, statusClass;
        if (bmi < 18.5) {
            status = 'Underweight';
            statusClass = 'badge-warning';
        } else if (bmi < 25) {
            status = 'Normal';
            statusClass = 'badge-success';
        } else if (bmi < 30) {
            status = 'Overweight';
            statusClass = 'badge-warning';
        } else {
            status = 'Obese';
            statusClass = 'badge-danger';
        }
        
        $('#bmi-value').text(bmiRounded);
        $('#bmi-status').html(`<span class="badge ${statusClass}">${status}</span>`);
        $('#bmi-info').show();
    } else {
        $('#bmi-info').hide();
    }
}

// Real-time validation and warnings
$('#weight').on('input', function() {
    calculateBMI();
});

$('#blood_pressure').on('input', function() {
    const bp = $(this).val();
    const bpPattern = /^(\d{2,3})\/(\d{2,3})$/;
    const match = bp.match(bpPattern);
    
    if (match) {
        const systolic = parseInt(match[1]);
        const diastolic = parseInt(match[2]);
        
        if (systolic >= 140 || diastolic >= 90) {
            $(this).addClass('border-danger');
            showWarning('blood_pressure', 'Tekanan darah tinggi - perlu perhatian khusus');
        } else if (systolic < 90 || diastolic < 60) {
            $(this).addClass('border-warning');
            showWarning('blood_pressure', 'Tekanan darah rendah - monitor kondisi pasien');
        } else {
            $(this).removeClass('border-danger border-warning');
            hideWarning('blood_pressure');
        }
    }
});

$('#temperature').on('input', function() {
    const temp = parseFloat($(this).val());
    
    if (temp >= 37.5) {
        $(this).addClass('border-danger');
        showWarning('temperature', 'Demam - segera informasikan ke dokter');
    } else if (temp < 36) {
        $(this).addClass('border-warning');
        showWarning('temperature', 'Suhu tubuh rendah - perlu pemantauan');
    } else {
        $(this).removeClass('border-danger border-warning');
        hideWarning('temperature');
    }
});

$('#heart_rate').on('input', function() {
    const hr = parseInt($(this).val());
    
    if (hr > 100) {
        $(this).addClass('border-danger');
        showWarning('heart_rate', 'Takikardia - detak jantung tinggi');
    } else if (hr < 60) {
        $(this).addClass('border-warning');
        showWarning('heart_rate', 'Bradikardia - detak jantung rendah');
    } else {
        $(this).removeClass('border-danger border-warning');
        hideWarning('heart_rate');
    }
});

function showWarning(field, message) {
    const warningId = `${field}-warning`;
    $(`#${warningId}`).remove();
    $(`#${field}`).after(`<div id="${warningId}" class="text-danger mt-1"><small><i class="fas fa-exclamation-triangle"></i> ${message}</small></div>`);
}

function hideWarning(field) {
    $(`#${field}-warning`).remove();
}

// Initialize BMI calculation on page load
$(document).ready(function() {
    calculateBMI();
});
</script>
@endpush
@endsection