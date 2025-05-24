@extends('layouts.app')

@section('title', 'Input Vital Signs')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Input Vital Signs</li>
@endsection

@section('content')
<!-- Header & Actions -->
<div class="row mb-3">
    <div class="col-md-6">
        <h1 class="h3 mb-0">
            <i class="fas fa-heartbeat text-danger mr-2"></i>
            Input Vital Signs
        </h1>
        <p class="text-muted">Pemeriksaan vital signs untuk pasien yang terdaftar</p>
    </div>
    <div class="col-md-6 text-right">
        <span class="badge badge-info badge-lg">{{ $records->total() }} pasien menunggu</span>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-user-plus"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pasien Terdaftar</span>
                <span class="info-box-number">{{ $records->total() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-heartbeat"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Selesai Hari Ini</span>
                <span class="info-box-number">{{ \App\Models\MedicalRecord::whereDate('vitals_at', today())->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Menunggu</span>
                <span class="info-box-number">{{ \App\Models\MedicalRecord::where('status', 'registered')->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-user-md"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Menunggu Dokter</span>
                <span class="info-box-number">{{ \App\Models\MedicalRecord::where('status', 'vitals_checked')->count() }}</span>
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
        <form method="GET" action="{{ route('vitals.index') }}">
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
                    <a href="{{ route('vitals.index') }}" class="btn btn-secondary">
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
            Pasien Menunggu Pemeriksaan Vital Signs
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
                        <th>Umur & Jenis Kelamin</th>
                        <th>Waktu Daftar</th>
                        <th>Status</th>
                        <th>Prioritas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                    @php
                        $waitingTime = $record->registered_at->diffInMinutes(now());
                        $priorityClass = $waitingTime > 60 ? 'danger' : ($waitingTime > 30 ? 'warning' : 'info');
                    @endphp
                    <tr class="{{ $waitingTime > 60 ? 'table-danger' : ($waitingTime > 30 ? 'table-warning' : '') }}">
                        <td>
                            <strong class="text-primary">{{ $record->visit_number }}</strong>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $record->patient->name }}</strong><br>
                                <small class="text-muted">
                                    {{ $record->patient->patient_number }}<br>
                                    <i class="fas fa-phone mr-1"></i>{{ $record->patient->phone }}
                                </small>
                            </div>
                        </td>
                        <td>
                            <span class="h6">{{ $record->patient->age }} tahun</span><br>
                            <span class="badge badge-{{ $record->patient->gender == 'L' ? 'primary' : 'pink' }}">
                                <i class="fas fa-{{ $record->patient->gender == 'L' ? 'mars' : 'venus' }} mr-1"></i>
                                {{ $record->patient->gender_display }}
                            </span>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $record->registered_at->format('H:i') }}</strong><br>
                                <small class="text-muted">{{ $record->registered_at->diffForHumans() }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $record->status_color }}">
                                {{ $record->status_display }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $priorityClass }}">
                                @if($waitingTime > 60)
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Urgent
                                @elseif($waitingTime > 30)
                                    <i class="fas fa-clock mr-1"></i>Priority
                                @else
                                    <i class="fas fa-info-circle mr-1"></i>Normal
                                @endif
                            </span>
                            <br><small class="text-muted">{{ $waitingTime }} menit</small>
                        </td>
                        <td>
                            <a href="{{ route('vitals.edit', $record) }}" 
                               class="btn btn-danger btn-sm">
                                <i class="fas fa-heartbeat mr-1"></i>
                                Input Vital Signs
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
                <a href="{{ route('vitals.index') }}" class="btn btn-secondary">Tampilkan Semua</a>
            @else
                <p class="text-muted">Semua pasien sudah dilakukan pemeriksaan vital signs</p>
                <a href="{{ route('dashboard.perawat') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Dashboard
                </a>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Vital Signs Guidelines -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Panduan Pemeriksaan Vital Signs
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6><i class="fas fa-weight text-primary"></i> Berat Badan</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• Pasien berdiri tegak</li>
                            <li>• Tanpa sepatu dan jaket</li>
                            <li>• Catat dalam kilogram (kg)</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-tachometer-alt text-warning"></i> Tekanan Darah</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• Normal: 90/60 - 140/90 mmHg</li>
                            <li>• Hipertensi: ≥140/90 mmHg</li>
                            <li>• Hipotensi: <90/60 mmHg</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-thermometer-half text-danger"></i> Suhu Tubuh</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• Normal: 36.1 - 37.2°C</li>
                            <li>• Demam ringan: 37.3 - 38°C</li>
                            <li>• Demam tinggi: >38°C</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-heartbeat text-success"></i> Detak Jantung</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• Normal: 60-100 bpm</li>
                            <li>• Takikardia: >100 bpm</li>
                            <li>• Bradikardia: <60 bpm</li>
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
</style>
@endsection