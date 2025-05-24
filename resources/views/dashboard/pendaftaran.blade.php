@extends('layouts.app')

@section('title', 'Dashboard Pendaftaran')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_patients'] }}</h3>
                <p>Total Pasien</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['today_registrations'] }}</h3>
                <p>Pendaftaran Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pending_vitals'] }}</h3>
                <p>Menunggu Vital Signs</p>
            </div>
            <div class="icon">
                <i class="fas fa-heartbeat"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $stats['completed_today'] }}</h3>
                <p>Selesai Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus-circle mr-1"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('patients.create') }}" class="btn btn-success btn-lg btn-block">
                            <i class="fas fa-user-plus"></i><br>
                            Daftar Pasien Baru
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('patients.index') }}" class="btn btn-info btn-lg btn-block">
                            <i class="fas fa-users"></i><br>
                            Lihat Data Pasien
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('medical-records.index') }}" class="btn btn-warning btn-lg btn-block">
                            <i class="fas fa-file-medical"></i><br>
                            Rekam Medis
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-secondary btn-lg btn-block" disabled>
                            <i class="fas fa-chart-line"></i><br>
                            Laporan Harian
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Patients & Pending Records -->
<div class="row">
    <!-- Recent Patients -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-1"></i>
                    Pasien Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No. Pasien</th>
                            <th>Nama</th>
                            <th>Umur</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_patients as $patient)
                        <tr>
                            <td>{{ $patient->patient_number }}</td>
                            <td>{{ $patient->name }}</td>
                            <td>{{ $patient->age }} tahun</td>
                            <td>
                                @php
                                    $latestRecord = $patient->medicalRecords()->latest()->first();
                                @endphp
                                @if($latestRecord)
                                    <span class="badge badge-{{ $latestRecord->status_color }}">
                                        {{ $latestRecord->status_display }}
                                    </span>
                                @else
                                    <span class="badge badge-secondary">Belum ada kunjungan</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data pasien</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Pending Records -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock mr-1"></i>
                    Menunggu Pemeriksaan
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No. Kunjungan</th>
                            <th>Nama Pasien</th>
                            <th>Waktu Daftar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pending_records as $record)
                        <tr>
                            <td>{{ $record->visit_number }}</td>
                            <td>{{ $record->patient->name }}</td>
                            <td>{{ $record->registered_at->format('H:i') }}</td>
                            <td>
                                <span class="badge badge-{{ $record->status_color }}">
                                    {{ $record->status_display }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Tidak ada yang menunggu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection