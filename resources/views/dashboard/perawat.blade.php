@extends('layouts.app')

@section('title', 'Dashboard Perawat')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
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
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['completed_vitals_today'] }}</h3>
                <p>Vital Signs Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['total_patients_today'] }}</h3>
                <p>Pasien Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $stats['waiting_doctor'] }}</h3>
                <p>Menunggu Dokter</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-md"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-heartbeat mr-1"></i>
                    Quick Actions - Perawat
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('vitals.index') }}" class="btn btn-info btn-lg btn-block">
                            <i class="fas fa-heartbeat"></i><br>
                            Input Vital Signs
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('patients.index') }}" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-users"></i><br>
                            Data Pasien
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('medical-records.index') }}" class="btn btn-success btn-lg btn-block">
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

<!-- Pending Vitals & Recent Work -->
<div class="row">
    <!-- Pending Vitals -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock mr-1"></i>
                    Pasien Menunggu Pemeriksaan Vital Signs
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No. Kunjungan</th>
                            <th>Nama Pasien</th>
                            <th>Umur</th>
                            <th>Jenis Kelamin</th>
                            <th>Waktu Daftar</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pending_vitals as $record)
                        <tr>
                            <td>
                                <strong>{{ $record->visit_number }}</strong>
                            </td>
                            <td>
                                <strong>{{ $record->patient->name }}</strong><br>
                                <small class="text-muted">{{ $record->patient->patient_number }}</small>
                            </td>
                            <td>{{ $record->patient->age }} tahun</td>
                            <td>
                                <span class="badge badge-{{ $record->patient->gender == 'L' ? 'primary' : 'pink' }}">
                                    {{ $record->patient->gender_display }}
                                </span>
                            </td>
                            <td>
                                <small>
                                    {{ $record->registered_at->format('H:i') }}<br>
                                    <span class="text-muted">{{ $record->registered_at->diffForHumans() }}</span>
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('vitals.edit', $record->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-heartbeat"></i> Input Vital
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-check-circle text-success fa-2x"></i><br>
                                Tidak ada pasien yang menunggu pemeriksaan vital signs
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Recent Vitals -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Vital Signs Terbaru
                </h3>
            </div>
            <div class="card-body">
                @forelse($recent_vitals as $record)
                <div class="callout callout-info">
                    <h5>{{ $record->patient->name }}</h5>
                    <p>
                        <strong>BB:</strong> {{ $record->weight }}kg |
                        <strong>TD:</strong> {{ $record->blood_pressure }}<br>
                        @if($record->temperature)
                            <strong>Suhu:</strong> {{ $record->temperature }}°C |
                        @endif
                        @if($record->heart_rate)
                            <strong>Nadi:</strong> {{ $record->heart_rate }} bpm
                        @endif
                    </p>
                    <small class="text-muted">
                        <i class="fas fa-clock"></i> {{ $record->vitals_at->format('H:i') }} - {{ $record->vitals_at->diffForHumans() }}
                    </small>
                </div>
                @empty
                <div class="text-center text-muted">
                    <i class="fas fa-heartbeat fa-2x"></i><br>
                    Belum ada vital signs hari ini
                </div>
                @endforelse
            </div>
        </div>

        <!-- Vital Signs Guidelines -->
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Panduan Vital Signs
                </h3>
            </div>
            <div class="card-body">
                <small>
                    <strong>Tekanan Darah Normal:</strong><br>
                    • Dewasa: 90/60 - 140/90 mmHg<br>
                    • Anak: 80/50 - 110/70 mmHg<br><br>
                    
                    <strong>Suhu Normal:</strong><br>
                    • Dewasa: 36.1 - 37.2°C<br>
                    • Anak: 36.6 - 38.0°C<br><br>
                    
                    <strong>Nadi Normal:</strong><br>
                    • Dewasa: 60-100 bpm<br>
                    • Anak: 70-120 bpm<br><br>
                    
                    <strong>BMI Normal:</strong><br>
                    • 18.5 - 24.9 kg/m²
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Vital Signs Summary Chart -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Ringkasan Vital Signs Hari Ini
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-thermometer-half"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Demam (>37.5°C)</span>
                                <span class="info-box-number">{{ collect($recent_vitals)->where('temperature', '>', 37.5)->count() }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" style="width: {{ collect($recent_vitals)->where('temperature', '>', 37.5)->count() * 20 }}%"></div>
                                </div>
                                <span class="progress-description">Perlu perhatian khusus</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-tachometer-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Hipertensi</span>
                                <span class="info-box-number">2</span>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" style="width: 40%"></div>
                                </div>
                                <span class="progress-description">TD >140/90 mmHg</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-weight"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">BMI Normal</span>
                                <span class="info-box-number">{{ $stats['completed_vitals_today'] - 2 }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-info" style="width: 70%"></div>
                                </div>
                                <span class="progress-description">18.5-24.9 kg/m²</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-heartbeat"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Nadi Normal</span>
                                <span class="info-box-number">{{ $stats['completed_vitals_today'] }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: 85%"></div>
                                </div>
                                <span class="progress-description">60-100 bpm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection