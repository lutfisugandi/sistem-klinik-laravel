@extends('layouts.app')

@section('title', 'Detail Obat')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('medicines.index') }}">Data Obat</a></li>
<li class="breadcrumb-item active">{{ $medicine->name }}</li>
@endsection

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-pills text-success mr-2"></i>
            {{ $medicine->name }}
        </h1>
        <p class="text-muted">
            {{ ucfirst($medicine->type) }} | 
            Ditambahkan: {{ $medicine->created_at->format('d F Y') }}
        </p>
    </div>
    <div class="col-md-4 text-right">
        <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-warning">
            <i class="fas fa-edit mr-1"></i>
            Edit Data
        </a>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#stockModal">
            <i class="fas fa-plus mr-1"></i>
            Update Stok
        </button>
    </div>
</div>

<!-- Medicine Information -->
<div class="row">
    <!-- Medicine Details -->
    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Informasi Obat
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="medicine-icon mx-auto mb-2" style="width: 80px; height: 80px; background: linear-gradient(135deg, #16a34a, #0d9488); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-pills fa-2x text-white"></i>
                    </div>
                    <h4 class="mb-0">{{ $medicine->name }}</h4>
                    <p class="text-muted">{{ ucfirst($medicine->type) }}</p>
                </div>

                <table class="table table-borderless">
                    <tr>
                        <td><strong>Jenis:</strong></td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($medicine->type) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Stok Saat Ini:</strong></td>
                        <td>
                            <span class="h4 mb-0 
                                {{ $medicine->stock <= 5 ? 'text-danger' : ($medicine->stock <= 10 ? 'text-warning' : 'text-success') }}">
                                {{ $medicine->stock }} {{ $medicine->unit }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status Stok:</strong></td>
                        <td>
                            @if($medicine->stock <= 5)
                                <span class="badge badge-danger">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Kritis
                                </span>
                            @elseif($medicine->stock <= 10)
                                <span class="badge badge-warning">
                                    <i class="fas fa-battery-quarter mr-1"></i>
                                    Rendah
                                </span>
                            @elseif($medicine->stock <= 50)
                                <span class="badge badge-info">
                                    <i class="fas fa-battery-half mr-1"></i>
                                    Sedang
                                </span>
                            @else
                                <span class="badge badge-success">
                                    <i class="fas fa-battery-full mr-1"></i>
                                    Baik
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Harga:</strong></td>
                        <td>{{ $medicine->formatted_price }} / {{ $medicine->unit }}</td>
                    </tr>
                    <tr>
                        <td><strong>Satuan:</strong></td>
                        <td>{{ ucfirst($medicine->unit) }}</td>
                    </tr>
                    @if($medicine->description)
                    <tr>
                        <td><strong>Deskripsi:</strong></td>
                        <td>{{ $medicine->description }}</td>
                    </tr>
                    @endif
                </table>

                @if($medicine->stock <= 10)
                <div class="alert alert-{{ $medicine->stock <= 5 ? 'danger' : 'warning' }}">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <strong>Perhatian!</strong> 
                    Stok obat {{ $medicine->stock <= 5 ? 'sangat rendah' : 'menipis' }}. 
                    Segera lakukan restock untuk mencegah kehabisan.
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-1"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#stockModal">
                        <i class="fas fa-plus mr-1"></i>
                        Update Stok
                    </button>
                    <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-edit mr-1"></i>
                        Edit Informasi
                    </a>
                    <a href="{{ route('medicines.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage Statistics & History -->
    <div class="col-md-8">
        <!-- Statistics -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-prescription"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Diresepkan</span>
                        <span class="info-box-number">{{ $prescriptionCount }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-chart-line"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Nilai Stok</span>
                        <span class="info-box-number">{{ 'Rp ' . number_format($medicine->stock * $medicine->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-calendar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Estimasi Habis</span>
                        <span class="info-box-number">
                            @if($prescriptionCount > 0 && $medicine->stock > 0)
                                {{ ceil($medicine->stock / ($prescriptionCount / max(1, $medicine->created_at->diffInDays(now())))) }} hari
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prescription History -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Riwayat Penggunaan Obat
                </h3>
                <div class="card-tools">
                    <span class="badge badge-info">{{ $prescriptionCount }} resep</span>
                </div>
            </div>
            <div class="card-body">
                @if($recentPrescriptions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pasien</th>
                                    <th>Dosis</th>
                                    <th>Jumlah</th>
                                    <th>Instruksi</th>
                                    <th>Dokter</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPrescriptions as $prescription)
                                <tr>
                                    <td>
                                        <small>{{ $prescription->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $prescription->medicalRecord->patient->name }}</strong><br>
                                        <small class="text-muted">{{ $prescription->medicalRecord->patient->patient_number }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $prescription->dosage }}</span>
                                    </td>
                                    <td>{{ $prescription->quantity }} {{ $medicine->unit }}</td>
                                    <td>
                                        <small>{{ $prescription->instructions }}</small>
                                    </td>
                                    <td>
                                        <small>dr. Budi Rahardjo, Sp.PD</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($prescriptionCount > 10)
                    <div class="text-center mt-3">
                        <p class="text-muted">Menampilkan 10 resep terbaru dari total {{ $prescriptionCount }} resep</p>
                    </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-prescription fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Pernah Digunakan</h5>
                        <p class="text-muted">Obat ini belum pernah diresepkan untuk pasien manapun</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Stock Movement Log (Future Enhancement) -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exchange-alt mr-1"></i>
                    Log Pergerakan Stok (Coming Soon)
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center py-3 text-muted">
                    <i class="fas fa-construction fa-2x mb-2"></i>
                    <p>Fitur log pergerakan stok akan segera tersedia</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stock Update Modal -->
<div class="modal fade" id="stockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="fas fa-plus mr-1"></i>
                    Update Stok: {{ $medicine->name }}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('medicines.update', $medicine) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>{{ $medicine->name }}</strong><br>
                        Stok saat ini: <strong>{{ $medicine->stock }}</strong> {{ $medicine->unit }}
                    </div>
                    
                    <!-- Keep other fields unchanged -->
                    <input type="hidden" name="name" value="{{ $medicine->name }}">
                    <input type="hidden" name="type" value="{{ $medicine->type }}">
                    <input type="hidden" name="description" value="{{ $medicine->description }}">
                    <input type="hidden" name="price" value="{{ $medicine->price }}">
                    <input type="hidden" name="unit" value="{{ $medicine->unit }}">
                    
                    <div class="form-group">
                        <label for="stock">Stok Baru</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                            </div>
                            <input type="number" class="form-control" id="stock" name="stock" 
                                   value="{{ $medicine->stock }}" min="0" required>
                            <div class="input-group-append">
                                <span class="input-group-text">{{ $medicine->unit }}</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Masukkan jumlah stok yang baru</small>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-success btn-block" onclick="addStock(10)">
                                <i class="fas fa-plus mr-1"></i>
                                +10
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-success btn-block" onclick="addStock(50)">
                                <i class="fas fa-plus mr-1"></i>
                                +50
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-success btn-block" onclick="addStock(100)">
                                <i class="fas fa-plus mr-1"></i>
                                +100
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i>
                        Update Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if($prescriptionCount == 0)
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Konfirmasi Hapus Obat
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus obat <strong>{{ $medicine->name }}</strong> dari inventory?</p>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i>
                        Ya, Hapus Obat
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add delete button to quick actions if no prescriptions -->
@push('scripts')
<script>
$(document).ready(function() {
    @if($prescriptionCount == 0)
    $('.card-success .card-body .d-grid').append(`
        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteModal">
            <i class="fas fa-trash mr-1"></i>
            Hapus Obat
        </button>
    `);
    @endif
});

function addStock(amount) {
    var currentStock = {{ $medicine->stock }};
    var newStock = currentStock + amount;
    $('#stock').val(newStock);
}
</script>
@endpush
@endif
@endsection