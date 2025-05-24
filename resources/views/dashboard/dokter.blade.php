@extends('layouts.app')

@section('title', 'Dashboard Dokter')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pending_diagnosis'] }}</h3>
                <p>Menunggu Diagnosis</p>
            </div>
            <div class="icon">
                <i class="fas fa-stethoscope"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['diagnosed_today'] }}</h3>
                <p>Didiagnosis Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-md"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_patients_today'] }}</h3>
                <p>Total Pasien Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $stats['completed_diagnosis'] }}</h3>
                <p>Total Diagnosis</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-medical-alt"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-stethoscope mr-1"></i>
                    Quick Actions - Dokter
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('diagnosis.index') }}" class="btn btn-warning btn-lg btn-block">
                            <i class="fas fa-stethoscope"></i><br>
                            Diagnosis Pasien
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('medical-records.index') }}" class="btn btn-info btn-lg btn-block">
                            <i class="fas fa-file-medical"></i><br>
                            Rekam Medis
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('patients.index') }}" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-users"></i><br>
                            Data Pasien
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-secondary btn-lg btn-block" disabled>
                            <i class="fas fa-chart-line"></i><br>
                            Laporan Medis
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Diagnosis & Recent Diagnosis -->
<div class="row">
    <!-- Pending Diagnosis -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock mr-1"></i>
                    Pasien Menunggu Diagnosis
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No. Kunjungan</th>
                            <th>Nama Pasien</th>
                            <th>Umur</th>
                            <th>Vital Signs</th>
                            <th>Waktu</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pending_diagnosis as $record)
                        <tr>
                            <td>{{ $record->visit_number }}</td>
                            <td>
                                <strong>{{ $record->patient->name }}</strong><br>
                                <small class="text-muted">{{ $record->patient->patient_number }}</small>
                            </td>
                            <td>{{ $record->patient->age }} thn</td>
                            <td>
                                @if($record->weight)
                                    <small>
                                        BB: {{ $record->weight }}kg<br>
                                        TD: {{ $record->blood_pressure }}<br>
                                        @if($record->temperature)
                                            Suhu: {{ $record->temperature }}°C
                                        @endif
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $record->vitals_at ? $record->vitals_at->format('H:i') : '-' }}</td>
                            <td>
                                <a href="{{ route('diagnosis.edit', $record->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-stethoscope"></i> Diagnosis
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-check-circle text-success fa-2x"></i><br>
                                Tidak ada pasien yang menunggu diagnosis
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Recent Diagnosis -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Diagnosis Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="timeline">
                    @forelse($recent_diagnosis as $record)
                    <div class="time-label">
                        <span class="bg-success">{{ $record->diagnosed_at->format('H:i') }}</span>
                    </div>
                    <div>
                        <i class="fas fa-stethoscope bg-warning"></i>
                        <div class="timeline-item">
                            <span class="time">
                                <i class="fas fa-clock"></i> {{ $record->diagnosed_at->diffForHumans() }}
                            </span>
                            <h3 class="timeline-header">
                                {{ $record->patient->name }}
                            </h3>
                            <div class="timeline-body">
                                <strong>Diagnosis:</strong> {{ Str::limit($record->diagnosis, 60) }}<br>
                                @if($record->complaints)
                                <strong>Keluhan:</strong> {{ Str::limit($record->complaints, 50) }}
                                @endif
                            </div>
                            <div class="timeline-footer">
                                <a href="{{ route('medical-records.show', $record->id) }}" class="btn btn-sm btn-info">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-file-medical fa-2x"></i><br>
                        Belum ada diagnosis hari ini
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Medical Tips -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Tips Medis Hari Ini
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-thermometer-half"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Demam</span>
                                <span class="info-box-number">Suhu >37.5°C</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 70%"></div>
                                </div>
                                <span class="progress-description">Perhatikan tanda bahaya</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-heartbeat"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tekanan Darah</span>
                                <span class="info-box-number">120/80 mmHg</span>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: 50%"></div>
                                </div>
                                <span class="progress-description">Normal untuk dewasa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-weight"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">BMI Normal</span>
                                <span class="info-box-number">18.5-24.9</span>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" style="width: 60%"></div>
                                </div>
                                <span class="progress-description">Indeks massa tubuh ideal</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection