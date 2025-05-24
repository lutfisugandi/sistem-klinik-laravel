@extends('layouts.app')

@section('title', 'Dashboard Apoteker')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pending_prescriptions'] }}</h3>
                <p>Resep Menunggu</p>
            </div>
            <div class="icon">
                <i class="fas fa-prescription"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['dispensed_today'] }}</h3>
                <p>Obat Diserahkan Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-pills"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['low_stock_medicines'] }}</h3>
                <p>Stok Menipis</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_medicines'] }}</h3>
                <p>Total Obat</p>
            </div>
            <div class="icon">
                <i class="fas fa-capsules"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-pills mr-1"></i>
                    Quick Actions - Apoteker
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('prescriptions.index') }}" class="btn btn-warning btn-lg btn-block">
                            <i class="fas fa-prescription"></i><br>
                            Proses Resep
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('medicines.index') }}" class="btn btn-success btn-lg btn-block">
                            <i class="fas fa-pills"></i><br>
                            Kelola Obat
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('medicines.create') }}" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-plus"></i><br>
                            Tambah Obat
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-secondary btn-lg btn-block" disabled>
                            <i class="fas fa-chart-pie"></i><br>
                            Laporan Farmasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Prescriptions & Low Stock Alert -->
<div class="row">
    <!-- Pending Prescriptions -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock mr-1"></i>
                    Resep Menunggu Diproses
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No. Kunjungan</th>
                            <th>Nama Pasien</th>
                            <th>Dokter</th>
                            <th>Diagnosis</th>
                            <th>Waktu</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pending_prescriptions as $record)
                        <tr>
                            <td>
                                <strong>{{ $record->visit_number }}</strong>
                            </td>
                            <td>
                                <strong>{{ $record->patient->name }}</strong><br>
                                <small class="text-muted">{{ $record->patient->patient_number }}</small>
                            </td>
                            <td>
                                <small>dr. Budi Rahardjo, Sp.PD</small>
                            </td>
                            <td>
                                <small>{{ Str::limit($record->diagnosis, 30) }}</small>
                            </td>
                            <td>
                                <small>
                                    {{ $record->diagnosed_at->format('H:i') }}<br>
                                    <span class="text-muted">{{ $record->diagnosed_at->diffForHumans() }}</span>
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('prescriptions.edit', $record->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-prescription"></i> Proses
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-check-circle text-success fa-2x"></i><br>
                                Tidak ada resep yang menunggu diproses
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Low Stock Alert -->
    <div class="col-md-4">
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Stok Menipis
                </h3>
            </div>
            <div class="card-body">
                @forelse($low_stock_medicines as $medicine)
                <div class="callout callout-danger">
                    <h5>{{ $medicine->name }}</h5>
                    <p>
                        <strong>Stok:</strong> 
                        <span class="badge badge-danger">{{ $medicine->stock }} {{ $medicine->unit }}</span><br>
                        <strong>Jenis:</strong> {{ ucfirst($medicine->type) }}<br>
                        <strong>Harga:</strong> {{ $medicine->formatted_price }}
                    </p>
                    <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-edit"></i> Update Stok
                    </a>
                </div>
                @empty
                <div class="text-center text-muted">
                    <i class="fas fa-check-circle text-success fa-2x"></i><br>
                    Semua stok obat mencukupi
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Medicine Search -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-search mr-1"></i>
                    Cari Obat Cepat
                </h3>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Nama obat..." id="quickSearch">
                    <div class="input-group-append">
                        <button class="btn btn-info" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <strong>Obat Populer:</strong><br>
                        • Paracetamol 500mg<br>
                        • Amoxicillin 500mg<br>
                        • ORS (Oralit)<br>
                        • Antasida DOEN
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Medicine Categories & Statistics -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Kategori Obat & Statistik Stok
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-tablets"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tablet</span>
                                <span class="info-box-number">5</span>
                                <div class="progress">
                                    <div class="progress-bar bg-info" style="width: 70%"></div>
                                </div>
                                <span class="progress-description">Stok: Baik</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-capsules"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Kapsul</span>
                                <span class="info-box-number">2</span>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: 60%"></div>
                                </div>
                                <span class="progress-description">Stok: Cukup</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-tint"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Sirup</span>
                                <span class="info-box-number">1</span>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" style="width: 30%"></div>
                                </div>
                                <span class="progress-description">Stok: Rendah</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-band-aid"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Antiseptik</span>
                                <span class="info-box-number">1</span>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" style="width: 20%"></div>
                                </div>
                                <span class="progress-description">Stok: Kritis</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-leaf"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Herbal</span>
                                <span class="info-box-number">1</span>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 50%"></div>
                                </div>
                                <span class="progress-description">Stok: Cukup</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary"><i class="fas fa-flask"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Suspensi</span>
                                <span class="info-box-number">1</span>
                                <div class="progress">
                                    <div class="progress-bar bg-secondary" style="width: 40%"></div>
                                </div>
                                <span class="progress-description">Stok: Cukup</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pharmacy Guidelines -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Panduan Farmasi
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h5><i class="fas fa-shield-alt text-danger"></i> Keamanan Obat</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Periksa tanggal kadaluarsa</li>
                            <li><i class="fas fa-check text-success"></i> Verifikasi dosis sesuai resep</li>
                            <li><i class="fas fa-check text-success"></i> Konfirmasi identitas pasien</li>
                            <li><i class="fas fa-check text-success"></i> Berikan informasi cara penggunaan</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5><i class="fas fa-thermometer-half text-warning"></i> Penyimpanan</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-snowflake text-info"></i> Kulkas: 2-8°C (insulin, vaksin)</li>
                            <li><i class="fas fa-home text-primary"></i> Suhu ruang: 15-30°C (tablet, kapsul)</li>
                            <li><i class="fas fa-eye-slash text-secondary"></i> Hindari cahaya langsung</li>
                            <li><i class="fas fa-tint text-danger"></i> Hindari kelembaban tinggi</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5><i class="fas fa-clock text-info"></i> Aturan Pakai</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-utensils text-success"></i> Sebelum makan: 30-60 menit sebelum</li>
                            <li><i class="fas fa-utensils text-warning"></i> Sesudah makan: 30 menit setelah</li>
                            <li><i class="fas fa-moon text-primary"></i> Sebelum tidur: 30 menit sebelum</li>
                            <li><i class="fas fa-exclamation-triangle text-danger"></i> Habiskan antibiotik</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection