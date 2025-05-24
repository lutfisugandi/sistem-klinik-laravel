@extends('layouts.app')

@section('title', 'Diagnosis Pasien')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Diagnosis Pasien</li>
@endsection

@section('content')
<!-- Header & Actions -->
<div class="row mb-3">
    <div class="col-md-6">
        <h1 class="h3 mb-0">
            <i class="fas fa-stethoscope text-warning mr-2"></i>
            Diagnosis Pasien
        </h1>
        <p class="text-muted">Pemeriksaan dan diagnosis untuk pasien yang sudah dilakukan vital signs</p>
    </div>
    <div class="col-md-6 text-right">
        <span class="badge badge-warning badge-lg">{{ $records->total() }} pasien menunggu</span>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-stethoscope"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Menunggu Diagnosis</span>
                <span class="info-box-number">{{ $records->total() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-user-md"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Selesai Hari Ini</span>
                <span class="info-box-number">{{ \App\Models\MedicalRecord::whereDate('diagnosed_at', today())->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-heartbeat"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Vital Signs Selesai</span>
                <span class="info-box-number">{{ \App\Models\MedicalRecord::where('status', 'vitals_checked')->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-pills"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Menunggu Resep</span>
                <span class="info-box-number">{{ \App\Models\MedicalRecord::where('status', 'diagnosed')->count() }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Search -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-search mr-1"></i>
            Cari Pasien
        </h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('diagnosis.index') }}">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Nama pasien atau nomor kunjungan...">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search mr-1"></i>
                        Cari
                    </button>
                    <a href="{{ route('diagnosis.index') }}" class="btn btn-secondary">
                        <i class="fas fa-undo mr-1"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Patients List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list mr-1"></i>
            Pasien Menunggu Diagnosis
        </h3>
    </div>
    <div class="card-body p-0">
        @if($records->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No. Kunjungan</th>
                        <th>Data Pasien</th>
                        <th>Vital Signs</th>
                        <th>Waktu Vital Signs</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                    @php
                        $waitingTime = $record->vitals_at ? $record->vitals_at->diffInMinutes(now()) : 0;
                        $priorityClass = $waitingTime > 60 ? 'danger' : ($waitingTime > 30 ? 'warning' : 'success');
                        
                        // Check for abnormal vitals
                        $hasAbnormalVitals = false;
                        $abnormalSigns = [];
                        
                        // Safe blood pressure check
                        if ($record->blood_pressure && is_string($record->blood_pressure)) {
                            $bpParts = explode('/', $record->blood_pressure);
                            if (count($bpParts) == 2) {
                                $systolic = (int)$bpParts[0];
                                $diastolic = (int)$bpParts[1];
                                if ($systolic >= 140 || $diastolic >= 90 || $systolic < 90 || $diastolic < 60) {
                                    $hasAbnormalVitals = true;
                                    $abnormalSigns[] = 'TD';
                                }
                            }
                        }
                        
                        // Safe temperature check
                        if ($record->temperature && is_numeric($record->temperature)) {
                            if ($record->temperature >= 37.5 || $record->temperature < 36) {
                                $hasAbnormalVitals = true;
                                $abnormalSigns[] = 'Suhu';
                            }
                        }
                        
                        // Safe heart rate check
                        if ($record->heart_rate && is_numeric($record->heart_rate)) {
                            if ($record->heart_rate > 100 || $record->heart_rate < 60) {
                                $hasAbnormalVitals = true;
                                $abnormalSigns[] = 'Nadi';
                            }
                        }
                    @endphp
                    <tr class="{{ $hasAbnormalVitals ? 'table-warning' : '' }}">
                        <td>
                            <strong class="text-primary">{{ $record->visit_number }}</strong>
                            @if($hasAbnormalVitals)
                                <br><span class="badge badge-warning badge-sm">
                                    <i class="fas fa-exclamation-triangle"></i> Abnormal
                                </span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <strong>{{ $record->patient->name }}</strong><br>
                                <small class="text-muted">
                                    {{ $record->patient->patient_number }}<br>
                                    {{ $record->patient->age }} thn | 
                                    {{ $record->patient->gender_display }}
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="vital-signs">
                                <small>
                                    @if($record->weight)
                                        <strong>BB:</strong> {{ $record->weight }}kg<br>
                                    @endif
                                    @if($record->blood_pressure)
                                        <strong>TD:</strong> 
                                        <span class="{{ in_array('TD', $abnormalSigns) ? 'text-warning' : '' }}">
                                            {{ $record->blood_pressure }}
                                        </span><br>
                                    @endif
                                    @if($record->temperature)
                                        <strong>Suhu:</strong> 
                                        <span class="{{ in_array('Suhu', $abnormalSigns) ? 'text-warning' : '' }}">
                                            {{ $record->temperature }}°C
                                        </span><br>
                                    @endif
                                    @if($record->heart_rate)
                                        <strong>Nadi:</strong> 
                                        <span class="{{ in_array('Nadi', $abnormalSigns) ? 'text-warning' : '' }}">
                                            {{ $record->heart_rate }} bpm
                                        </span>
                                    @endif
                                </small>
                            </div>
                        </td>
                        <td>
                            <div>
                                @if($record->vitals_at)
                                    <strong>{{ $record->vitals_at->format('H:i') }}</strong><br>
                                    <small class="text-muted">{{ $record->vitals_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($hasAbnormalVitals)
                                <span class="badge badge-danger">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Urgent
                                </span>
                                @if(count($abnormalSigns) > 0)
                                    <br><small class="text-muted">{{ implode(', ', $abnormalSigns) }}</small>
                                @endif
                            @else
                                <span class="badge badge-{{ $priorityClass }}">
                                    @if($waitingTime > 60)
                                        <i class="fas fa-clock mr-1"></i>High
                                    @elseif($waitingTime > 30)
                                        <i class="fas fa-clock mr-1"></i>Medium
                                    @else
                                        <i class="fas fa-check mr-1"></i>Normal
                                    @endif
                                </span>
                                <br><small class="text-muted">{{ $waitingTime }} menit</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $record->status_color }}">
                                {{ $record->status_display }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('diagnosis.edit', $record) }}" 
                               class="btn btn-warning btn-sm">
                                <i class="fas fa-stethoscope mr-1"></i>
                                {{ $record->diagnosis ? 'Edit' : 'Input' }} Diagnosis
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="card-footer">
            {{ $records->withQueryString()->links() }}
        </div>
        
        @else
        <div class="text-center py-5">
            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
            <h5 class="text-muted">Tidak Ada Pasien Menunggu</h5>
            @if(request()->filled('search'))
                <p class="text-muted">Tidak ditemukan pasien dengan kata kunci "{{ request('search') }}"</p>
                <a href="{{ route('diagnosis.index') }}" class="btn btn-secondary">Tampilkan Semua</a>
            @else
                <p class="text-muted">Semua pasien sudah dilakukan diagnosis</p>
                <a href="{{ route('dashboard.dokter') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Dashboard
                </a>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Medical Guidelines -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Panduan Diagnosis & Red Flags
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6><i class="fas fa-exclamation-triangle text-danger"></i> Red Flags Vital Signs</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• TD Sistole >180 atau <90 mmHg</li>
                            <li>• Suhu >39°C atau <35°C</li>
                            <li>• Nadi >120 atau <50 bpm</li>
                            <li>• Sesak napas berat</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-stethoscope text-primary"></i> Anamnesis</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• Keluhan utama & sejak kapan</li>
                            <li>• Riwayat penyakit sekarang</li>
                            <li>• Riwayat penyakit dahulu</li>
                            <li>• Riwayat pengobatan</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-search text-success"></i> Pemeriksaan Fisik</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• Inspeksi umum</li>
                            <li>• Pemeriksaan sistemik</li>
                            <li>• Fokus sesuai keluhan</li>
                            <li>• Dokumentasi lengkap</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-diagnoses text-warning"></i> Diagnosis</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• Diagnosis kerja</li>
                            <li>• Diagnosis banding</li>
                            <li>• Rencana pemeriksaan lanjut</li>
                            <li>• Edukasi pasien</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-sm {
    font-size: 0.875rem;
}

.badge-lg {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}

.vital-signs {
    font-size: 0.9rem;
}

.badge-sm {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
}
</style>
@endsection