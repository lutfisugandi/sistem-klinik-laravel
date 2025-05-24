@extends('layouts.app')

@section('title', 'Detail Pasien')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Data Pasien</a></li>
<li class="breadcrumb-item active">{{ $patient->name }}</li>
@endsection

@section('content')
<!-- Patient Header -->
<div class="row mb-3">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-user text-primary mr-2"></i>
            {{ $patient->name }}
        </h1>
        <p class="text-muted">No. Pasien: <strong>{{ $patient->patient_number }}</strong> | Terdaftar: {{ $patient->created_at->format('d F Y') }}</p>
    </div>
    <div class="col-md-4 text-right">
        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-warning">
            <i class="fas fa-edit mr-1"></i>
            Edit Data
        </a>
        <a href="{{ route('patients.medical-record', $patient) }}" class="btn btn-success">
            <i class="fas fa-plus mr-1"></i>
            Kunjungan Baru
        </a>
    </div>
</div>

<!-- Patient Information -->
<div class="row">
    <!-- Patient Basic Info -->
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

                <table class="table table-borderless">
                    <tr>
                        <td><strong>Umur:</strong></td>
                        <td>{{ $patient->age }} tahun</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Lahir:</strong></td>
                        <td>{{ $patient->birth_date->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kelamin:</strong></td>
                        <td>
                            <span class="badge badge-{{ $patient->gender == 'L' ? 'primary' : 'pink' }}">
                                <i class="fas fa-{{ $patient->gender == 'L' ? 'mars' : 'venus' }} mr-1"></i>
                                {{ $patient->gender_display }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>No. Telepon:</strong></td>
                        <td>
                            <a href="tel:{{ $patient->phone }}" class="text-decoration-none">
                                <i class="fas fa-phone text-success mr-1"></i>
                                {{ $patient->phone }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Alamat:</strong></td>
                        <td>{{ $patient->address ?: 'Tidak ada data' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    Statistik Kunjungan
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 text-center">
                        <div class="info-box-content">
                            <span class="info-box-text">Total Kunjungan</span>
                            <span class="info-box-number text-primary">{{ $patient->medicalRecords->count() }}</span>
                        </div>
                    </div>
                    <div class="col-6 text-center">
                        <div class="info-box-content">
                            <span class="info-box-text">Kunjungan Selesai</span>
                            <span class="info-box-number text-success">{{ $patient->medicalRecords->where('status', 'completed')->count() }}</span>
                        </div>
                    </div>
                </div>
                
                @if($patient->medicalRecords->count() > 0)
                <hr>
                <small class="text-muted">
                    <strong>Kunjungan Terakhir:</strong><br>
                    {{ $patient->medicalRecords->first()->created_at->format('d F Y') }}
                    ({{ $patient->medicalRecords->first()->created_at->diffForHumans() }})
                </small>
                @endif
            </div>
        </div>
    </div>

    <!-- Medical Records -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-medical mr-1"></i>
                    Riwayat Rekam Medis
                </h3>
                <div class="card-tools">
                    <span class="badge badge-info">{{ $medicalRecords->total() }} kunjungan</span>
                </div>
            </div>
            <div class="card-body">
                @if($medicalRecords->count() > 0)
                    @foreach($medicalRecords as $record)
                    <div class="timeline-item">
                        <div class="timeline-badge bg-{{ $record->status_color }}">
                            <i class="fas fa-{{ $record->status == 'completed' ? 'check' : 'clock' }}"></i>
                        </div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title">
                                    Kunjungan {{ $record->visit_number }}
                                    <span class="badge badge-{{ $record->status_color }} ml-2">
                                        {{ $record->status_display }}
                                    </span>
                                </h4>
                                <p class="text-muted">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $record->created_at->format('d F Y, H:i') }}
                                    ({{ $record->created_at->diffForHumans() }})
                                </p>
                            </div>
                            <div class="timeline-body">
                                <div class="row">
                                    @if($record->weight || $record->blood_pressure)
                                    <div class="col-md-6">
                                        <strong>Vital Signs:</strong>
                                        <ul class="list-unstyled mb-1">
                                            @if($record->weight)
                                                <li><i class="fas fa-weight mr-1"></i> BB: {{ $record->weight }} kg</li>
                                            @endif
                                            @if($record->blood_pressure)
                                                <li><i class="fas fa-tachometer-alt mr-1"></i> TD: {{ $record->blood_pressure }}</li>
                                            @endif
                                            @if($record->temperature)
                                                <li><i class="fas fa-thermometer-half mr-1"></i> Suhu: {{ $record->temperature }}°C</li>
                                            @endif
                                        </ul>
                                    </div>
                                    @endif
                                    
                                    @if($record->complaints || $record->diagnosis)
                                    <div class="col-md-6">
                                        @if($record->complaints)
                                            <strong>Keluhan:</strong>
                                            <p class="mb-1">{{ $record->complaints }}</p>
                                        @endif
                                        
                                        @if($record->diagnosis)
                                            <strong>Diagnosis:</strong>
                                            <p class="mb-1">{{ $record->diagnosis }}</p>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                
                                @if($record->prescriptions->count() > 0)
                                <div class="mt-2">
                                    <strong>Resep Obat:</strong>
                                    <div class="row">
                                        @foreach($record->prescriptions as $prescription)
                                        <div class="col-md-6">
                                            <div class="callout callout-success callout-sm">
                                                <strong>{{ $prescription->medicine->name }}</strong><br>
                                                <small>
                                                    Dosis: {{ $prescription->dosage }} | 
                                                    Jumlah: {{ $prescription->quantity }} {{ $prescription->medicine->unit }}<br>
                                                    Aturan: {{ $prescription->instructions }}
                                                </small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="timeline-footer">
                                <a href="{{ route('medical-records.show', $record) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye mr-1"></i>
                                    Detail Lengkap
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                    
                    <!-- Pagination -->
                    <div class="text-center">
                        {{ $medicalRecords->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-medical fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Rekam Medis</h5>
                        <p class="text-muted">Pasien belum pernah melakukan kunjungan</p>
                        <a href="{{ route('patients.medical-record', $patient) }}" class="btn btn-success">
                            <i class="fas fa-plus mr-1"></i>
                            Buat Kunjungan Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="text-center">
            <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i>
                Kembali ke Daftar Pasien
            </a>
            <a href="{{ route('patients.edit', $patient) }}" class="btn btn-warning">
                <i class="fas fa-edit mr-1"></i>
                Edit Data Pasien
            </a>
            <a href="{{ route('patients.medical-record', $patient) }}" class="btn btn-success">
                <i class="fas fa-plus mr-1"></i>
                Kunjungan Baru
            </a>
            @if($patient->medicalRecords->count() == 0)
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                <i class="fas fa-trash mr-1"></i>
                Hapus Pasien
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if($patient->medicalRecords->count() == 0)
<form id="delete-form" action="{{ route('patients.destroy', $patient) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus data pasien ini? Tindakan ini tidak dapat dibatalkan.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endif

<style>
.timeline-item {
    position: relative;
    padding-left: 60px;
    margin-bottom: 20px;
}

.timeline-badge {
    position: absolute;
    left: 0;
    top: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
}

.timeline-panel {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
}

.callout-sm {
    padding: 8px 12px;
    margin-bottom: 5px;
}
</style>
@endsection