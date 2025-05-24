@extends('layouts.app')

@section('title', 'Edit Data Obat')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('medicines.index') }}">Data Obat</a></li>
<li class="breadcrumb-item"><a href="{{ route('medicines.show', $medicine) }}">{{ $medicine->name }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-md-12">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit text-warning mr-2"></i>
            Edit Data Obat
        </h1>
        <p class="text-muted">Memperbarui informasi obat: <strong>{{ $medicine->name }}</strong></p>
    </div>
</div>

<!-- Form -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-form mr-1"></i>
                    Form Edit Obat
                </h3>
            </div>
            <form action="{{ route('medicines.update', $medicine) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Nama Obat -->
                    <div class="form-group">
                        <label for="name" class="required">Nama Obat</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pills"></i></span>
                            </div>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $medicine->name) }}"
                                   required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jenis & Satuan -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type" class="required">Jenis Obat</label>
                                <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Pilih Jenis Obat</option>
                                    @php
                                        $types = ['tablet', 'kapsul', 'sirup', 'serbuk', 'salep', 'tetes', 'antiseptik', 'herbal', 'suspensi', 'injeksi', 'lainnya'];
                                    @endphp
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ old('type', $medicine->type) == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit" class="required">Satuan</label>
                                <select class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" required>
                                    <option value="">Pilih Satuan</option>
                                    @php
                                        $units = ['tablet', 'kapsul', 'botol', 'tube', 'sachet', 'vial', 'ampul', 'ml', 'gram', 'pcs'];
                                    @endphp
                                    @foreach($units as $unit)
                                        <option value="{{ $unit }}" {{ old('unit', $medicine->unit) == $unit ? 'selected' : '' }}>
                                            {{ ucfirst($unit) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description', $medicine->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stok & Harga -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock" class="required">Stok</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                                    </div>
                                    <input type="number" 
                                           class="form-control @error('stock') is-invalid @enderror" 
                                           id="stock" 
                                           name="stock" 
                                           value="{{ old('stock', $medicine->stock) }}"
                                           min="0"
                                           required>
                                </div>
                                @error('stock')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Stok saat ini: {{ $medicine->stock }} {{ $medicine->unit }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price" class="required">Harga per Satuan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price', $medicine->price) }}"
                                           step="0.01"
                                           min="0"
                                           required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stock Buttons -->
                    <div class="form-group">
                        <label>Quick Stock Update</label>
                        <div class="row">
                            <div class="col-md-3">
                                <button type="button" class="btn btn-success btn-sm btn-block" onclick="addStock(10)">
                                    <i class="fas fa-plus mr-1"></i>
                                    +10
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-success btn-sm btn-block" onclick="addStock(50)">
                                    <i class="fas fa-plus mr-1"></i>
                                    +50
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-success btn-sm btn-block" onclick="addStock(100)">
                                    <i class="fas fa-plus mr-1"></i>
                                    +100
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-warning btn-sm btn-block" onclick="resetStock()">
                                    <i class="fas fa-undo mr-1"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('medicines.show', $medicine) }}" class="btn btn-secondary">
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
                                Simpan Perubahan
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
                    Informasi Obat
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="medicine-icon mx-auto mb-2" style="width: 60px; height: 60px; background: linear-gradient(135deg, #16a34a, #0d9488); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-pills fa-lg text-white"></i>
                    </div>
                    <h5 class="mb-0">{{ $medicine->name }}</h5>
                    <p class="text-muted">{{ ucfirst($medicine->type) }}</p>
                </div>

                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>Stok Awal:</strong></td>
                        <td>{{ $medicine->stock }} {{ $medicine->unit }}</td>
                    </tr>
                    <tr>
                        <td><strong>Harga Awal:</strong></td>
                        <td>{{ $medicine->formatted_price }}</td>
                    </tr>
                    <tr>
                        <td><strong>Diresepkan:</strong></td>
                        <td>{{ $medicine->prescriptions->count() }} kali</td>
                    </tr>
                    <tr>
                        <td><strong>Ditambahkan:</strong></td>
                        <td>{{ $medicine->created_at->format('d/m/Y') }}</td>
                    </tr>
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
                    <strong>Perhatian!</strong> Perubahan data obat akan mempengaruhi semua resep yang menggunakan obat ini.
                </p>
                @if($medicine->prescriptions->count() > 0)
                <p class="text-muted">
                    Obat ini sudah digunakan dalam {{ $medicine->prescriptions->count() }} resep. 
                    Pastikan perubahan yang dilakukan tidak mengganggu data historis.
                </p>
                @else
                <p class="text-muted">
                    Obat ini belum pernah digunakan dalam resep, sehingga aman untuk diubah atau dihapus.
                </p>
                @endif
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

@push('scripts')
<script>
const originalStock = {{ $medicine->stock }};

function addStock(amount) {
    var currentStock = parseInt($('#stock').val()) || 0;
    var newStock = currentStock + amount;
    $('#stock').val(newStock);
}

function resetStock() {
    $('#stock').val(originalStock);
}
</script>
@endpush
@endsection