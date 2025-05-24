@extends('layouts.app')

@section('title', 'Data Pasien')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Data Pasien</li>
@endsection

@section('content')
<!-- Header & Actions -->
<div class="row mb-3">
    <div class="col-md-6">
        <h1 class="h3 mb-0">
            <i class="fas fa-users text-primary mr-2"></i>
            Data Pasien
        </h1>
        <p class="text-muted">Kelola data pasien klinik</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('patients.create') }}" class="btn btn-success">
            <i class="fas fa-user-plus mr-1"></i>
            Daftar Pasien Baru
        </a>
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
        <form method="GET" action="{{ route('patients.index') }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="search">Cari Pasien</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Nama, nomor pasien, atau nomor telepon...">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="">Semua</option>
                            <option value="L" {{ request('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ request('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i>
                                Cari
                            </button>
                            <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo mr-1"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Patients Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list mr-1"></i>
            Daftar Pasien ({{ $patients->total() }} pasien)
        </h3>
    </div>
    <div class="card-body p-0">
        @if($patients->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No. Pasien</th>
                        <th>Nama Pasien</th>
                        <th>Umur</th>
                        <th>Jenis Kelamin</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Terakhir Kunjungan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $patient->patient_number }}</strong>
                        </td>
                        <td>
                            <strong>{{ $patient->name }}</strong><br>
                            <small class="text-muted">Terdaftar: {{ $patient->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td>{{ $patient->age }} tahun</td>
                        <td>
                            <span class="badge badge-{{ $patient->gender == 'L' ? 'primary' : 'pink' }}">
                                <i class="fas fa-{{ $patient->gender == 'L' ? 'mars' : 'venus' }} mr-1"></i>
                                {{ $patient->gender_display }}
                            </span>
                        </td>
                        <td>
                            <a href="tel:{{ $patient->phone }}" class="text-decoration-none">
                                <i class="fas fa-phone text-success mr-1"></i>
                                {{ $patient->phone }}
                            </a>
                        </td>
                        <td>
                            <small>{{ Str::limit($patient->address, 40) }}</small>
                        </td>
                        <td>
                            @php
                                $lastRecord = $patient->medicalRecords()->latest()->first();
                            @endphp
                            @if($lastRecord)
                                <small>
                                    {{ $lastRecord->created_at->format('d/m/Y') }}<br>
                                    <span class="badge badge-{{ $lastRecord->status_color }}">
                                        {{ $lastRecord->status_display }}
                                    </span>
                                </small>
                            @else
                                <small class="text-muted">Belum pernah</small>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('patients.show', $patient) }}" 
                                   class="btn btn-info btn-sm" 
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('patients.edit', $patient) }}" 
                                   class="btn btn-warning btn-sm" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('patients.medical-record', $patient) }}" 
                                   class="btn btn-success btn-sm" 
                                   title="Daftar Kunjungan">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="card-footer">
            {{ $patients->withQueryString()->links() }}
        </div>
        
        @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Tidak ada data pasien</h5>
            @if(request()->filled('search'))
                <p class="text-muted">Tidak ditemukan pasien dengan kata kunci "{{ request('search') }}"</p>
                <a href="{{ route('patients.index') }}" class="btn btn-secondary">Tampilkan Semua Pasien</a>
            @else
                <p class="text-muted">Belum ada pasien yang terdaftar</p>
                <a href="{{ route('patients.create') }}" class="btn btn-success">
                    <i class="fas fa-user-plus mr-1"></i>
                    Daftar Pasien Pertama
                </a>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Quick Stats -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Pasien</span>
                <span class="info-box-number">{{ $patients->total() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-user-plus"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pasien Baru Hari Ini</span>
                <span class="info-box-number">{{ \App\Models\Patient::whereDate('created_at', today())->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-mars"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Laki-laki</span>
                <span class="info-box-number">{{ \App\Models\Patient::where('gender', 'L')->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-venus"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Perempuan</span>
                <span class="info-box-number">{{ \App\Models\Patient::where('gender', 'P')->count() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection