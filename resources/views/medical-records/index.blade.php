@extends('layouts.app')

@section('title', 'Rekam Medis')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Rekam Medis</li>
@endsection

@section('content')
<!-- Header & Actions -->
<div class="row mb-3">
    <div class="col-md-6">
        <h1 class="h3 mb-0">
            <i class="fas fa-file-medical text-info mr-2"></i>
            Rekam Medis Pasien
        </h1>
        <p class="text-muted">Kelola dan lihat riwayat rekam medis pasien</p>
    </div>
    <div class="col-md-6 text-right">
        @if(Auth::user()->isPendaftaran())
        <a href="{{ route('patients.create') }}" class="btn btn-success">
            <i class="fas fa-user-plus mr-1"></i>
            Daftar Pasien Baru
        </a>
        @endif
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-file-medical"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Kunjungan</span>
                <span class="info-box-number">{{ $records->total() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Dalam Proses</span>
                <span class="info-box-number">{{ \App\Models\MedicalRecord::whereIn('status', ['registered', 'vitals_checked', 'diagnosed'])->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Selesai Hari Ini</span>
                <span class="info-box-number">{{ \App\Models\MedicalRecord::whereDate('completed_at', today())->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-calendar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Kunjungan Hari Ini</span>
                <span class="info-box-number">{{ \App\Models\MedicalRecord::whereDate('created_at', today())->count() }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-filter mr-1"></i>
            Filter & Pencarian
        </h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('medical-records.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search">Cari Rekam Medis</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Nama pasien atau nomor kunjungan...">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="registered" {{ request('status') == 'registered' ? 'selected' : '' }}>Terdaftar</option>
                            <option value="vitals_checked" {{ request('status') == 'vitals_checked' ? 'selected' : '' }}>Cek Vital</option>
                            <option value="diagnosed" {{ request('status') == 'diagnosed' ? 'selected' : '' }}>Didiagnosis</option>
                            <option value="prescribed" {{ request('status') == 'prescribed' ? 'selected' : '' }}>Diresepkan</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date">Tanggal</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search mr-1"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Medical Records Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list mr-1"></i>
            Daftar Rekam Medis ({{ $records->total() }} kunjungan)
        </h3>
    </div>
    <div class="card-body p-0">
        @if($records->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No. Kunjungan</th>
                        <th>Pasien</th>
                        <th>Tanggal & Waktu</th>
                        <th>Status</th>
                        <th>Vital Signs</th>
                        <th>Diagnosis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $record->visit_number }}</strong>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $record->patient->name }}</strong><br>
                                <small class="text-muted">
                                    {{ $record->patient->patient_number }} | 
                                    {{ $record->patient->age }} tahun | 
                                    {{ $record->patient->gender_display }}
                                </small>
                            </div>
                        </td>
                        <td>
                            <div>
                                {{ $record->created_at->format('d/m/Y') }}<br>
                                <small class="text-muted">{{ $record->created_at->format('H:i') }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $record->status_color }}">
                                {{ $record->status_display }}
                            </span>
                        </td>
                        <td>
                            @if($record->weight)
                                <small>
                                    BB: {{ $record->weight }}kg<br>
                                    TD: {{ $record->blood_pressure }}
                                    @if($record->temperature)
                                        <br>Suhu: {{ $record->temperature }}°C
                                    @endif
                                </small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($record->diagnosis)
                                <small>{{ Str::limit($record->diagnosis, 40) }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('medical-records.show', $record) }}" 
                                   class="btn btn-info btn-sm" 
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if(Auth::user()->isPerawat() && $record->canUpdateVitals())
                                    <a href="{{ route('vitals.edit', $record) }}" 
                                       class="btn btn-primary btn-sm" 
                                       title="Input Vital Signs">
                                        <i class="fas fa-heartbeat"></i>
                                    </a>
                                @endif
                                
                                @if(Auth::user()->isDokter() && $record->canDiagnose())
                                    <a href="{{ route('diagnosis.edit', $record) }}" 
                                       class="btn btn-warning btn-sm" 
                                       title="Input Diagnosis">
                                        <i class="fas fa-stethoscope"></i>
                                    </a>
                                @endif
                                
                                @if(Auth::user()->isApoteker() && $record->canPrescribe())
                                    <a href="{{ route('prescriptions.edit', $record) }}" 
                                       class="btn btn-success btn-sm" 
                                       title="Buat Resep">
                                        <i class="fas fa-prescription"></i>
                                    </a>
                                @endif
                            </div>
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
            <i class="fas fa-file-medical fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Tidak ada rekam medis</h5>
            @if(request()->filled('search'))
                <p class="text-muted">Tidak ditemukan rekam medis dengan kata kunci "{{ request('search') }}"</p>
                <a href="{{ route('medical-records.index') }}" class="btn btn-secondary">Tampilkan Semua</a>
            @else
                <p class="text-muted">Belum ada kunjungan pasien yang tercatat</p>
                @if(Auth::user()->isPendaftaran())
                    <a href="{{ route('patients.create') }}" class="btn btn-success">
                        <i class="fas fa-user-plus mr-1"></i>
                        Daftar Pasien Pertama
                    </a>
                @endif
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Workflow Guide -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Alur Kerja Rekam Medis
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center">
                        <div class="workflow-step">
                            <div class="step-icon bg-primary">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h6>1. Pendaftaran</h6>
                            <small>Admin mendaftarkan pasien dan membuat kunjungan baru</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="workflow-step">
                            <div class="step-icon bg-info">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <h6>2. Vital Signs</h6>
                            <small>Perawat mengukur berat badan, tekanan darah, dan suhu</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="workflow-step">
                            <div class="step-icon bg-warning">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <h6>3. Pemeriksaan</h6>
                            <small>Dokter memeriksa dan memberikan diagnosis</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="workflow-step">
                            <div class="step-icon bg-success">
                                <i class="fas fa-prescription"></i>
                            </div>
                            <h6>4. Resep</h6>
                            <small>Apoteker memberikan obat sesuai resep dokter</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="workflow-step">
                            <div class="step-icon bg-secondary">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h6>5. Selesai</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.workflow-step {
    margin-bottom: 20px;
}

.step-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    color: white;
    font-size: 20px;
}

.workflow-step h6 {
    margin: 10px 0 5px;
    font-weight: bold;
}

.workflow-step small {
    color: #6c757d;
    line-height: 1.3;
}
</style>
@endsection