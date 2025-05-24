@extends('layouts.app')

@section('title', 'Diagnosis Pasien')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('diagnosis.index') }}">Diagnosis Pasien</a></li>
<li class="breadcrumb-item active">{{ $record->patient->name }}</li>
@endsection

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-md-12">
        <h1 class="h3 mb-0">
            <i class="fas fa-stethoscope text-warning mr-2"></i>
            {{ $record->diagnosis ? 'Edit' : 'Input' }} Diagnosis
        </h1>
        <p class="text-muted">
            Pasien: <strong>{{ $record->patient->name }}</strong> | 
            No. Kunjungan: <strong>{{ $record->visit_number }}</strong> | 
            {{ $record->vitals_at ? $record->vitals_at->format('d F Y, H:i') : 'Vital signs belum dilakukan' }}
        </p>
    </div>
</div>

<!-- Patient Info & Vital Signs -->
<div class="row">
    <!-- Patient Information & Vital Signs -->
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
                    <div class="avatar-circle mx-auto mb-2" style="width: 80px; height: 80px; background: linear-gradient(135deg, #ffc107, #fd7e14); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
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

        <!-- Vital Signs -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-heartbeat mr-1"></i>
                    Vital Signs
                </h3>
                <div class="card-tools">
                    <span class="badge badge-info">{{ $record->vitals_at ? $record->vitals_at->format('H:i') : 'Belum' }}</span>
                </div>
            </div>
            <div class="card-body">
                @if($record->weight)
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="description-block">
                                <h5 class="description-header">{{ $record->weight }}</h5>
                                <span class="description-text">kg</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="description-block">
                                <h5 class="description-header">{{ $record->blood_pressure }}</h5>
                                <span class="description-text">mmHg</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($record->temperature || $record->heart_rate)
                    <hr>
                    <div class="row text-center">
                        @if($record->temperature)
                        <div class="col-6">
                            <div class="description-block">
                                <h5 class="description-header text-{{ $record->temperature >= 37.5 ? 'danger' : 'success' }}">
                                    {{ $record->temperature }}°C
                                </h5>
                                <span class="description-text">Suhu</span>
                            </div>
                        </div>
                        @endif
                        @if($record->heart_rate)
                        <div class="col-6">
                            <div class="description-block">
                                <h5 class="description-header text-{{ $record->heart_rate > 100 || $record->heart_rate < 60 ? 'warning' : 'success' }}">
                                    {{ $record->heart_rate }}
                                </h5>
                                <span class="description-text">bpm</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- BMI -->
                    @if($record->bmi)
                    <hr>
                    <div class="text-center">
                        <div class="description-block">
                            <h5 class="description-header text-{{ $record->bmi < 18.5 || $record->bmi > 24.9 ? 'warning' : 'success' }}">
                                {{ $record->bmi }}
                            </h5>
                            <span class="description-text">BMI</span>
                        </div>
                    </div>
                    @endif

                    <!-- Abnormal Signs Warning -->
                    @php
                        $abnormalSigns = [];
                        if ($record->blood_pressure) {
                            [$systolic, $diastolic] = explode('/', $record->blood_pressure);
                            if ($systolic >= 140 || $diastolic >= 90) $abnormalSigns[] = 'Hipertensi';
                            if ($systolic < 90 || $diastolic < 60) $abnormalSigns[] = 'Hipotensi';
                        }
                        if ($record->temperature && $record->temperature >= 37.5) $abnormalSigns[] = 'Demam';
                        if ($record->heart_rate && $record->heart_rate > 100) $abnormalSigns[] = 'Takikardia';
                        if ($record->heart_rate && $record->heart_rate < 60) $abnormalSigns[] = 'Bradikardia';
                    @endphp
                    
                    @if(count($abnormalSigns) > 0)
                    <div class="alert alert-warning mt-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian:</strong> {{ implode(', ', $abnormalSigns) }}
                    </div>
                    @endif
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <p>Vital signs belum diinput</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Previous Medical History -->
        @php
            $previousRecords = $record->patient->medicalRecords()
                ->where('id', '!=', $record->id)
                ->whereNotNull('diagnosis')
                ->latest()
                ->take(3)
                ->get();
        @endphp
        @if($previousRecords->count() > 0)
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Riwayat Diagnosis
                </h3>
            </div>
            <div class="card-body">
                @foreach($previousRecords as $prevRecord)
                <div class="callout callout-info callout-sm">
                    <h6>{{ $prevRecord->diagnosed_at->format('d/m/Y') }}</h6>
                    <p class="mb-0"><strong>{{ $prevRecord->diagnosis }}</strong></p>
                    @if($prevRecord->notes)
                        <small class="text-muted">{{ Str::limit($prevRecord->notes, 50) }}</small>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Diagnosis Form -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-stethoscope mr-1"></i>
                    Form Diagnosis
                </h3>
            </div>
            <form action="{{ route('diagnosis.update', $record) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Keluhan Pasien -->
                    <div class="form-group">
                        <label for="complaints" class="required">Keluhan Pasien (Anamnesis)</label>
                        <textarea class="form-control @error('complaints') is-invalid @enderror" 
                                  id="complaints" 
                                  name="complaints" 
                                  rows="4"
                                  placeholder="Jelaskan keluhan utama pasien, sejak kapan, faktor yang memperparah/memperingan, gejala penyerta, dll."
                                  required>{{ old('complaints', $record->complaints) }}</textarea>
                        @error('complaints')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Tuliskan keluhan utama dan riwayat penyakit sekarang secara detail</small>
                    </div>

                    <!-- Diagnosis -->
                    <div class="form-group">
                        <label for="diagnosis" class="required">Diagnosis</label>
                        <textarea class="form-control @error('diagnosis') is-invalid @enderror" 
                                  id="diagnosis" 
                                  name="diagnosis" 
                                  rows="3"
                                  placeholder="Tuliskan diagnosis kerja dan diagnosis banding (jika ada)"
                                  required>{{ old('diagnosis', $record->diagnosis) }}</textarea>
                        @error('diagnosis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Diagnosis kerja berdasarkan anamnesis dan pemeriksaan fisik</small>
                    </div>

                    <!-- Catatan Dokter -->
                    <div class="form-group">
                        <label for="notes">Catatan Dokter</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3"
                                  placeholder="Catatan tambahan, rencana pemeriksaan lanjut, edukasi pasien, dll.">{{ old('notes', $record->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Catatan tambahan, anjuran, atau rencana tindak lanjut (opsional)</small>
                    </div>

                    <!-- Common Diagnoses Helper -->
                    <div class="form-group">
                        <label>Quick Templates (Klik untuk menggunakan)</label>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Diagnosis Umum:</h6>
                                <div class="btn-group-vertical btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary mb-1" onclick="setDiagnosis('ISPA (Infeksi Saluran Pernapasan Atas)')">ISPA</button>
                                    <button type="button" class="btn btn-outline-primary mb-1" onclick="setDiagnosis('Gastritis Akut')">Gastritis</button>
                                    <button type="button" class="btn btn-outline-primary mb-1" onclick="setDiagnosis('Hipertensi Stadium 1')">Hipertensi</button>
                                    <button type="button" class="btn btn-outline-primary mb-1" onclick="setDiagnosis('Dermatitis Alergi')">Dermatitis</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Kondisi Demam:</h6>
                                <div class="btn-group-vertical btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-warning mb-1" onclick="setDiagnosis('Demam Berdarah Dengue (DBD) Grade I')">DBD</button>
                                    <button type="button" class="btn btn-outline-warning mb-1" onclick="setDiagnosis('Typhoid Fever (Demam Tifoid)')">Tifoid</button>
                                    <button type="button" class="btn btn-outline-warning mb-1" onclick="setDiagnosis('Viral Fever (Demam Viral)')">Demam Viral</button>
                                    <button type="button" class="btn btn-outline-warning mb-1" onclick="setDiagnosis('Influenza')">Influenza</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('diagnosis.index') }}" class="btn btn-secondary">
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
                                Simpan Diagnosis
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Medical Reference -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-book-medical mr-1"></i>
                    Referensi Medis Cepat
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6><i class="fas fa-thermometer-half text-danger"></i> Klasifikasi Demam</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• <strong>Subfebrile:</strong> 37.3-38°C</li>
                            <li>• <strong>Demam:</strong> 38.1-39°C</li>
                            <li>• <strong>Demam Tinggi:</strong> 39.1-40°C</li>
                            <li>• <strong>Hiperpireksia:</strong> >40°C</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-tachometer-alt text-warning"></i> Klasifikasi TD</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• <strong>Normal:</strong> <120/80</li>
                            <li>• <strong>Pre-hipertensi:</strong> 120-139/80-89</li>
                            <li>• <strong>HT Stage 1:</strong> 140-159/90-99</li>
                            <li>• <strong>HT Stage 2:</strong> ≥160/100</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-weight text-info"></i> Klasifikasi BMI</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• <strong>Underweight:</strong> <18.5</li>
                            <li>• <strong>Normal:</strong> 18.5-24.9</li>
                            <li>• <strong>Overweight:</strong> 25-29.9</li>
                            <li>• <strong>Obese:</strong> ≥30</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-heartbeat text-success"></i> Nadi Normal</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• <strong>Dewasa:</strong> 60-100 bpm</li>
                            <li>• <strong>Anak:</strong> 70-120 bpm</li>
                            <li>• <strong>Takikardia:</strong> >100 bpm</li>
                            <li>• <strong>Bradikardia:</strong> <60 bpm</li>
                        </ul>
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

.text-sm {
    font-size: 0.875rem;
}

.callout-sm {
    padding: 8px 12px;
    margin-bottom: 10px;
}

.description-block {
    margin: 0;
}

.description-header {
    font-size: 1.5rem;
    margin: 0;
    font-weight: bold;
}

.description-text {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
}
</style>

@push('scripts')
<script>
function setDiagnosis(diagnosis) {
    $('#diagnosis').val(diagnosis);
    $('#diagnosis').focus();
}

// Auto-resize textareas
$('textarea').on('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

// Word count for textareas
$('#complaints, #diagnosis, #notes').each(function() {
    const textarea = $(this);
    const maxLength = 1000;
    const counterId = textarea.attr('id') + '-count';
    
    textarea.after(`<small class="form-text text-muted" id="${counterId}">0/${maxLength} karakter</small>`);
    
    textarea.on('input', function() {
        const currentLength = $(this).val().length;
        $(`#${counterId}`).text(`${currentLength}/${maxLength} karakter`);
        
        if (currentLength > maxLength * 0.9) {
            $(`#${counterId}`).addClass('text-warning');
        } else {
            $(`#${counterId}`).removeClass('text-warning');
        }
        
        if (currentLength > maxLength) {
            $(`#${counterId}`).addClass('text-danger').removeClass('text-warning');
        } else {
            $(`#${counterId}`).removeClass('text-danger');
        }
    }).trigger('input');
});

// Real-time validation
$('#complaints, #diagnosis').on('blur', function() {
    if ($(this).val().trim() === '') {
        $(this).addClass('is-invalid');
    } else {
        $(this).removeClass('is-invalid').addClass('is-valid');
    }
});

// Form submission validation
$('form').on('submit', function(e) {
    let isValid = true;
    
    $('#complaints, #diagnosis').each(function() {
        if ($(this).val().trim() === '') {
            $(this).addClass('is-invalid');
            isValid = false;
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        toastr.error('Harap lengkapi semua field yang wajib diisi');
        $('html, body').animate({
            scrollTop: $('.is-invalid:first').offset().top - 100
        }, 500);
    }
});
</script>
@endpush
@endsection