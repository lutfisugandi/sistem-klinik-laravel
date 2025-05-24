@extends('layouts.app')

@section('title', 'Edit Data Pasien')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Data Pasien</a></li>
<li class="breadcrumb-item"><a href="{{ route('patients.show', $patient) }}">{{ $patient->name }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-md-12">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit text-warning mr-2"></i>
            Edit Data Pasien
        </h1>
        <p class="text-muted">Memperbarui data pasien: <strong>{{ $patient->name }}</strong> ({{ $patient->patient_number }})</p>
    </div>
</div>

<!-- Edit Form -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-form mr-1"></i>
                    Form Edit Pasien
                </h3>
            </div>
            <form action="{{ route('patients.update', $patient) }}" method="POST">
                @csrf
                @method('PUT')
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
                                   value="{{ old('name', $patient->name) }}"
                                   placeholder="Masukkan nama lengkap pasien"
                                   required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
                                   value="{{ old('birth_date', $patient->birth_date->format('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                        </div>
                        @error('birth_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Umur saat ini: {{ $patient->age }} tahun</small>
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
                                           {{ old('gender', $patient->gender) == 'L' ? 'checked' : '' }}>
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
                                           {{ old('gender', $patient->gender) == 'P' ? 'checked' : '' }}>
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
                                   value="{{ old('phone', $patient->phone) }}"
                                   placeholder="Contoh: 081234567890"
                                   pattern="[0-9]{10,15}"
                                   required>
                        </div>
                        @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
                                      placeholder="Masukkan alamat lengkap pasien">{{ old('address', $patient->address) }}</textarea>
                        </div>
                        @error('address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('patients.show', $patient) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="reset" class="btn btn-warning">
                                <i class="fas fa-undo mr-1"></i>
                                Reset
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Patient Info Panel -->
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Informasi Pasien
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-circle mx-auto mb-2" style="width: 60px; height: 60px; background: linear-gradient(135deg, #2563eb, #0d9488); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user fa-lg text-white"></i>
                    </div>
                    <h5 class="mb-0">{{ $patient->name }}</h5>
                    <p class="text-muted">{{ $patient->patient_number }}</p>
                </div>

                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>Terdaftar:</strong></td>
                        <td>{{ $patient->created_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Kunjungan:</strong></td>
                        <td>{{ $patient->medicalRecords->count() }} kali</td>
                    </tr>
                    @if($patient->medicalRecords->count() > 0)
                    <tr>
                        <td><strong>Kunjungan Terakhir:</strong></td>
                        <td>{{ $patient->medicalRecords->first()->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Peringatan
                </h3>
            </div>
            <div class="card-body">
                <p class="text-warning">
                    <strong>Perhatian!</strong> Perubahan data pasien akan mempengaruhi semua rekam medis yang terkait.
                </p>
                <p class="text-muted">
                    Pastikan data yang dimasukkan sudah benar dan sesuai dengan identitas resmi pasien.
                </p>
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