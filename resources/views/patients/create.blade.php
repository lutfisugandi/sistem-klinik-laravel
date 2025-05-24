@extends('layouts.app')

@section('title', 'Daftar Pasien Baru')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Data Pasien</a></li>
<li class="breadcrumb-item active">Daftar Pasien Baru</li>
@endsection

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-md-12">
        <h1 class="h3 mb-0">
            <i class="fas fa-user-plus text-success mr-2"></i>
            Daftar Pasien Baru
        </h1>
        <p class="text-muted">Masukkan data pasien baru untuk mendaftarkan ke sistem klinik</p>
    </div>
</div>

<!-- Registration Form -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-form mr-1"></i>
                    Form Pendaftaran Pasien
                </h3>
            </div>
            <form action="{{ route('patients.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <!-- Nama Pasien -->
                    <div class="form-group">
                        <label for="name" class="required">Nama Lengkap Pasien</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   placeholder="Masukkan nama lengkap pasien"
                                   required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Nama harus sesuai dengan identitas resmi</small>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="form-group">
                        <label for="birth_date" class="required">Tanggal Lahir</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            </div>
                            <input type="date" 
                                   class="form-control @error('birth_date') is-invalid @enderror" 
                                   id="birth_date" 
                                   name="birth_date" 
                                   value="{{ old('birth_date') }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                        </div>
                        @error('birth_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Umur akan dihitung otomatis dari tanggal lahir</small>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="form-group">
                        <label class="required">Jenis Kelamin</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input @error('gender') is-invalid @enderror" 
                                           type="radio" 
                                           id="gender_l" 
                                           name="gender" 
                                           value="L" 
                                           {{ old('gender') == 'L' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="gender_l">
                                        <i class="fas fa-mars text-primary mr-1"></i>
                                        Laki-laki
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input @error('gender') is-invalid @enderror" 
                                           type="radio" 
                                           id="gender_p" 
                                           name="gender" 
                                           value="P" 
                                           {{ old('gender') == 'P' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="gender_p">
                                        <i class="fas fa-venus text-danger mr-1"></i>
                                        Perempuan
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('gender')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="form-group">
                        <label for="phone" class="required">Nomor Telepon</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="tel" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   placeholder="Contoh: 081234567890"
                                   pattern="[0-9]{10,15}"
                                   required>
                        </div>
                        @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Nomor telepon yang aktif dan bisa dihubungi</small>
                    </div>

                    <!-- Alamat -->
                    <div class="form-group">
                        <label for="address">Alamat Lengkap</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            </div>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3"
                                      placeholder="Masukkan alamat lengkap pasien">{{ old('address') }}</textarea>
                        </div>
                        @error('address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Alamat rumah atau domisili saat ini (opsional)</small>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="reset" class="btn btn-warning">
                                <i class="fas fa-undo mr-1"></i>
                                Reset Form
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i>
                                Daftar Pasien
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Information Panel -->
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Informasi Pendaftaran
                </h3>
            </div>
            <div class="card-body">
                <div class="callout callout-info">
                    <h5><i class="fas fa-id-card"></i> Nomor Pasien</h5>
                    <p>Nomor pasien akan dibuat otomatis setelah pendaftaran berhasil dengan format: <strong>P{tanggal}{urutan}</strong></p>
                </div>

                <div class="callout callout-success">
                    <h5><i class="fas fa-shield-alt"></i> Keamanan Data</h5>
                    <p>Semua data pasien akan disimpan dengan aman dan hanya dapat diakses oleh staff medis yang berwenang.</p>
                </div>

                <div class="callout callout-warning">
                    <h5><i class="fas fa-exclamation-triangle"></i> Data Wajib</h5>
                    <p>Pastikan semua data yang bertanda <span class="text-danger">*</span> diisi dengan benar dan sesuai identitas resmi.</p>
                </div>
            </div>
        </div>

        <!-- Recent Patients -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Pasien Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                @php
                    $recentPatients = \App\Models\Patient::latest()->take(5)->get();
                @endphp
                @forelse($recentPatients as $patient)
                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                    <div>
                        <strong>{{ $patient->name }}</strong><br>
                        <small class="text-muted">{{ $patient->patient_number }}</small>
                    </div>
                    <small class="text-muted">{{ $patient->created_at->diffForHumans() }}</small>
                </div>
                @empty
                <div class="text-center p-3 text-muted">
                    Belum ada pasien terdaftar
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
.required:after {
    content: ' *';
    color: red;
}
</style>
@endsection