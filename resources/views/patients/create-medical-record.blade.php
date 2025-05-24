@extends('layouts.app')

@section('title', 'Kunjungan Baru')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Data Pasien</a></li>
<li class="breadcrumb-item"><a href="{{ route('patients.show', $patient) }}">{{ $patient->name }}</a></li>
<li class="breadcrumb-item active">Kunjungan Baru</li>
@endsection

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-md-12">
        <h1 class="h3 mb-0">
            <i class="fas fa-plus text-success mr-2"></i>
            Daftar Kunjungan Baru
        </h1>
        <p class="text-muted">Membuat kunjungan baru untuk pasien: <strong>{{ $patient->name }}</strong></p>
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
                    <div class="avatar-circle mx-auto mb-2" style="width: 80px; height: 80px; background: linear-gradient(135deg, #2563eb, #0d9488); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user fa-2x text-white"></i>
                    </div>
                    <h4 class="mb-0">{{ $patient->name }}</h4>
                    <p class="text-muted">{{ $patient->patient_number }}</p>
                </div>

                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Umur:</strong></td>
                        <td>{{ $patient->age }} tahun</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kelamin:</strong></td>
                        <td>
                            <span class="badge badge-{{ $patient->gender == 'L' ? 'primary' : 'pink' }}">
                                {{ $patient->gender_display }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>No. Telepon:</strong></td>
                        <td>{{ $patient->phone }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat:</strong></td>
                        <td>{{ $patient->address ?: 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Kunjungan:</strong></td>
                        <td>{{ $patient->medicalRecords->count() }} kali</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Last Visit Info -->
        @php
            $lastVisit = $patient->medicalRecords()->latest()->first();
        @endphp
        @if($lastVisit)
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Kunjungan Terakhir
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>No. Kunjungan:</strong></td>
                        <td>{{ $lastVisit->visit_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal:</strong></td>
                        <td>{{ $lastVisit->created_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge badge-{{ $lastVisit->status_color }}">
                                {{ $lastVisit->status_display }}
                            </span>
                        </td>
                    </tr>
                    @if($lastVisit->diagnosis)
                    <tr>
                        <td><strong>Diagnosis:</strong></td>
                        <td>{{ Str::limit($lastVisit->diagnosis, 50) }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- New Visit Form -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus mr-1"></i>
                    Konfirmasi Kunjungan Baru
                </h3>
            </div>
            <div class="card-body">
                <!-- Check for active visit today -->
                @php
                    $activeToday = $patient->medicalRecords()
                        ->whereDate('created_at', today())
                        ->where('status', '!=', 'completed')
                        ->first();
                @endphp

                @if($activeToday)
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle mr-1"></i> Kunjungan Aktif Ditemukan</h5>
                        <p>Pasien sudah memiliki kunjungan aktif hari ini:</p>
                        <ul class="mb-2">
                            <li><strong>No. Kunjungan:</strong> {{ $activeToday->visit_number }}</li>
                            <li><strong>Status:</strong> 
                                <span class="badge badge-{{ $activeToday->status_color }}">
                                    {{ $activeToday->status_display }}
                                </span>
                            </li>
                            <li><strong>Waktu Daftar:</strong> {{ $activeToday->registered_at->format('H:i') }}</li>
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('medical-records.show', $activeToday) }}" class="btn btn-info">
                                <i class="fas fa-eye mr-1"></i>
                                Lihat Kunjungan Aktif
                            </a>
                            <a href="{{ route('patients.show', $patient) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali ke Profile
                            </a>
                        </div>
                    </div>
                @else
                    <!-- New Visit Form -->
                    <div class="text-center mb-4">
                        <div class="visit-icon mx-auto mb-3" style="width: 100px; height: 100px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-hospital-user fa-3x text-white"></i>
                        </div>
                        <h4>Buat Kunjungan Baru</h4>
                        <p class="text-muted">Sistem akan otomatis membuat nomor kunjungan dan mengatur status awal</p>
                    </div>

                    <div class="info-cards mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-calendar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Tanggal Kunjungan</span>
                                        <span class="info-box-number">{{ now()->format('d F Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-info">
                                    <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Waktu Pendaftaran</span>
                                        <span class="info-box-number">{{ now()->format('H:i') }} WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="workflow-preview mb-4">
                        <h6>Alur Pemeriksaan:</h6>
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <div class="step-icon bg-primary mb-2">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <small>Pendaftaran</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="step-icon bg-info mb-2">
                                    <i class="fas fa-heartbeat"></i>
                                </div>
                                <small>Vital Signs</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="step-icon bg-warning mb-2">
                                    <i class="fas fa-stethoscope"></i>
                                </div>
                                <small>Diagnosis</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="step-icon bg-success mb-2">
                                    <i class="fas fa-prescription"></i>
                                </div>
                                <small>Resep</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="step-icon bg-secondary mb-2">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <small>Selesai</small>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('patients.store-medical-record', $patient) }}" method="POST">
                        @csrf
                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-plus mr-2"></i>
                                Buat Kunjungan Baru
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('patients.show', $patient) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali ke Profile
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('patients.index') }}" class="btn btn-info">
                            <i class="fas fa-users mr-1"></i>
                            Daftar Pasien
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-size: 18px;
}

.info-cards .info-box {
    margin-bottom: 0;
}

.workflow-preview {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}
</style>
@endsection