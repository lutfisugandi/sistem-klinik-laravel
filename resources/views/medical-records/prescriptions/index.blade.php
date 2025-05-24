@extends('layouts.app')

@section('title', 'Resep Obat')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Resep Obat</li>
@endsection

@section('content')
<!-- Header & Actions -->
<div class="row mb-3">
    <div class="col-md-6">
        <h1 class="h3 mb-0">
            <i class="fas fa-prescription text-success mr-2"></i>
            Resep Obat
        </h1>
        <p class="text-muted">Proses resep obat untuk pasien yang sudah didiagnosis</p>
    </div>
    <div class="col-md-6 text-right">
        <span class="badge badge-success badge-lg">{{ $records->total() }} resep menunggu</span>
        <a href="{{ route('medicines.index') }}" class="btn btn-info">
            <i class="fas fa-pills mr-1"></i>
            Kelola Obat
        </a>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-prescription"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Menunggu Resep</span>
                <span class="info-box-number">{{ $records->total() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-pills"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Diserahkan Hari Ini</span>
                <span class="info-box-number">{{ \App\Models\MedicalRecord::whereDate('prescribed_at', today())->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Stok Menipis</span>
                <span class="info-box-number">{{ \App\Models\Medicine::where('stock', '<=', 10)->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Selesai Hari Ini</span>
                <span class="info-box-number">{{ \App\Models\MedicalRecord::whereDate('completed_at', today())->count() }}</span>
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
        <form method="GET" action="{{ route('prescriptions.index') }}">
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
                    <a href="{{ route('prescriptions.index') }}" class="btn btn-secondary">
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
            Pasien Menunggu Pembuatan Resep
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
                        <th>Diagnosis</th>
                        <th>Waktu Diagnosis</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                    @php
                        $waitingTime = $record->diagnosed_at ? $record->diagnosed_at->diffInMinutes(now()) : 0;
                        $priorityClass = $waitingTime > 120 ? 'danger' : ($waitingTime > 60 ? 'warning' : 'info');
                        
                        // Check for urgent conditions
                        $isUrgent = false;
                        $urgentConditions = ['demam berdarah', 'dbd', 'hipertensi', 'diabetes', 'asma', 'pneumonia'];
                        foreach ($urgentConditions as $condition) {
                            if (stripos($record->diagnosis, $condition) !== false) {
                                $isUrgent = true;
                                break;
                            }
                        }
                    @endphp
                    <tr class="{{ $isUrgent ? 'table-warning' : ($waitingTime > 120 ? 'table-danger' : '') }}">
                        <td>
                            <strong class="text-primary">{{ $record->visit_number }}</strong>
                            @if($isUrgent)
                                <br><span class="badge badge-danger badge-sm">
                                    <i class="fas fa-exclamation-triangle"></i> Urgent
                                </span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <strong>{{ $record->patient->name }}</strong><br>
                                <small class="text-muted">
                                    {{ $record->patient->patient_number }}<br>
                                    {{ $record->patient->age }} thn | 
                                    {{ $record->patient->gender_display }}<br>
                                    <i class="fas fa-phone mr-1"></i>{{ $record->patient->phone }}
                                </small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ Str::limit($record->diagnosis, 50) }}</strong>
                                @if($record->notes)
                                    <br><small class="text-muted">{{ Str::limit($record->notes, 40) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $record->diagnosed_at->format('H:i') }}</strong><br>
                                <small class="text-muted">{{ $record->diagnosed_at->diffForHumans() }}</small>
                            </div>
                        </td>
                        <td>
                            @if($isUrgent)
                                <span class="badge badge-danger">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Urgent
                                </span>
                                <br><small class="text-muted">Kondisi khusus</small>
                            @else
                                <span class="badge badge-{{ $priorityClass }}">
                                    @if($waitingTime > 120)
                                        <i class="fas fa-clock mr-1"></i>High
                                    @elseif($waitingTime > 60)
                                        <i class="fas fa-clock mr-1"></i>Medium
                                    @else
                                        <i class="fas fa-check mr-1"></i>Normal
                                    @endif
                                </span>
                                <br><small class="text-muted">{{ $waitingTime }} menit</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $record->status_color }}">
                                {{ $record->status_display }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('prescriptions.edit', $record) }}" 
                               class="btn btn-success btn-sm">
                                <i class="fas fa-prescription mr-1"></i>
                                {{ $record->prescriptions->count() > 0 ? 'Edit' : 'Buat' }} Resep
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
            <h5 class="text-muted">Tidak Ada Resep Menunggu</h5>
            @if(request()->filled('search'))
                <p class="text-muted">Tidak ditemukan pasien dengan kata kunci "{{ request('search') }}"</p>
                <a href="{{ route('prescriptions.index') }}" class="btn btn-secondary">Tampilkan Semua</a>
            @else
                <p class="text-muted">Semua resep sudah diproses</p>
                <a href="{{ route('dashboard.apoteker') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Dashboard
                </a>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Low Stock Alert -->
@php
    $lowStockMedicines = \App\Models\Medicine::where('stock', '<=', 10)->orderBy('stock')->take(5)->get();
@endphp
@if($lowStockMedicines->count() > 0)
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Peringatan Stok Obat Menipis
                </h3>
                <div class="card-tools">
                    <a href="{{ route('medicines.index', ['sort' => 'stock_asc']) }}" class="btn btn-tool">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($lowStockMedicines as $medicine)
                    <div class="col-md-{{ $lowStockMedicines->count() >= 5 ? '2' : '3' }}">
                        <div class="info-box bg-{{ $medicine->stock <= 5 ? 'danger' : 'warning' }}">
                            <span class="info-box-icon"><i class="fas fa-pills"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ Str::limit($medicine->name, 15) }}</span>
                                <span class="info-box-number">{{ $medicine->stock }} {{ $medicine->unit }}</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ min(100, ($medicine->stock / 50) * 100) }}%"></div>
                                </div>
                                <span class="progress-description">
                                    @if($medicine->stock <= 5)
                                        Stok Kritis
                                    @else
                                        Stok Rendah
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Pharmacy Guidelines -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Panduan Farmasi & Keselamatan Obat
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6><i class="fas fa-shield-alt text-danger"></i> 5 Benar Pemberian Obat</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• <strong>Benar Pasien:</strong> Cek identitas</li>
                            <li>• <strong>Benar Obat:</strong> Sesuai resep</li>
                            <li>• <strong>Benar Dosis:</strong> Jumlah tepat</li>
                            <li>• <strong>Benar Cara:</strong> Oral/topikal/dll</li>
                            <li>• <strong>Benar Waktu:</strong> Sesuai aturan</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-clock text-warning"></i> Aturan Pakai</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• <strong>1x1:</strong> Sekali sehari</li>
                            <li>• <strong>2x1:</strong> Pagi dan sore</li>
                            <li>• <strong>3x1:</strong> Pagi, siang, malam</li>
                            <li>• <strong>PRN:</strong> Bila perlu/gejala muncul</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-utensils text-success"></i> Hubungan dengan Makanan</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• <strong>AC:</strong> Sebelum makan (30-60 mnt)</li>
                            <li>• <strong>PC:</strong> Sesudah makan (30 mnt)</li>
                            <li>• <strong>DC:</strong> Saat makan</li>
                            <li>• <strong>HS:</strong> Sebelum tidur</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-exclamation-triangle text-danger"></i> Obat Khusus</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• <strong>Antibiotik:</strong> Harus habis</li>
                            <li>• <strong>Kortikosteroid:</strong> Tidak boleh stop mendadak</li>
                            <li>• <strong>Antikoagulan:</strong> Monitor perdarahan</li>
                            <li>• <strong>Insulin:</strong> Simpan di kulkas</li>
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

.badge-sm {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
}
</style>
@endsection