@extends('layouts.app')

@section('title', 'Tambah Obat Baru')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('medicines.index') }}">Data Obat</a></li>
<li class="breadcrumb-item active">Tambah Obat</li>
@endsection

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-md-12">
        <h1 class="h3 mb-0">
            <i class="fas fa-plus text-success mr-2"></i>
            Tambah Obat Baru ke Inventory
        </h1>
        <p class="text-muted">Masukkan informasi obat baru untuk ditambahkan ke sistem inventory</p>
    </div>
</div>

<!-- Form -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-form mr-1"></i>
                    Form Data Obat
                </h3>
            </div>
            <form action="{{ route('medicines.store') }}" method="POST">
                @csrf
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
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Paracetamol 500mg"
                                   required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Nama obat harus unik dan spesifik (termasuk dosis jika ada)</small>
                    </div>

                    <!-- Jenis & Deskripsi -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type" class="required">Jenis Obat</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                    </div>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="">Pilih Jenis Obat</option>
                                        <option value="tablet" {{ old('type') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                        <option value="kapsul" {{ old('type') == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                                        <option value="sirup" {{ old('type') == 'sirup' ? 'selected' : '' }}>Sirup</option>
                                        <option value="serbuk" {{ old('type') == 'serbuk' ? 'selected' : '' }}>Serbuk</option>
                                        <option value="salep" {{ old('type') == 'salep' ? 'selected' : '' }}>Salep</option>
                                        <option value="tetes" {{ old('type') == 'tetes' ? 'selected' : '' }}>Tetes</option>
                                        <option value="antiseptik" {{ old('type') == 'antiseptik' ? 'selected' : '' }}>Antiseptik</option>
                                        <option value="herbal" {{ old('type') == 'herbal' ? 'selected' : '' }}>Herbal</option>
                                        <option value="suspensi" {{ old('type') == 'suspensi' ? 'selected' : '' }}>Suspensi</option>
                                        <option value="injeksi" {{ old('type') == 'injeksi' ? 'selected' : '' }}>Injeksi</option>
                                        <option value="lainnya" {{ old('type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                                @error('type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit" class="required">Satuan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                    </div>
                                    <select class="form-control @error('unit') is-invalid @enderror" 
                                            id="unit" 
                                            name="unit" 
                                            required>
                                        <option value="">Pilih Satuan</option>
                                        <option value="tablet" {{ old('unit') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                        <option value="kapsul" {{ old('unit') == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                                        <option value="botol" {{ old('unit') == 'botol' ? 'selected' : '' }}>Botol</option>
                                        <option value="tube" {{ old('unit') == 'tube' ? 'selected' : '' }}>Tube</option>
                                        <option value="sachet" {{ old('unit') == 'sachet' ? 'selected' : '' }}>Sachet</option>
                                        <option value="vial" {{ old('unit') == 'vial' ? 'selected' : '' }}>Vial</option>
                                        <option value="ampul" {{ old('unit') == 'ampul' ? 'selected' : '' }}>Ampul</option>
                                        <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>ml</option>
                                        <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram</option>
                                        <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                    </select>
                                </div>
                                @error('unit')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">
                        <label for="description">Deskripsi / Keterangan</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                            </div>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Contoh: Obat penurun demam dan pereda nyeri, diminum sesudah makan">{{ old('description') }}</textarea>
                        </div>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Deskripsi singkat tentang kegunaan dan cara konsumsi obat (opsional)</small>
                    </div>

                    <!-- Stok & Harga -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock" class="required">Stok Awal</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                                    </div>
                                    <input type="number" 
                                           class="form-control @error('stock') is-invalid @enderror" 
                                           id="stock" 
                                           name="stock" 
                                           value="{{ old('stock') }}"
                                           min="0"
                                           placeholder="0"
                                           required>
                                </div>
                                @error('stock')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Jumlah stok awal obat yang akan dimasukkan ke inventory</small>
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
                                           value="{{ old('price') }}"
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00"
                                           required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Harga per satuan obat (dalam Rupiah)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Warning Alert -->
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle mr-1"></i> Informasi Stok</h5>
                        <ul class="mb-0">
                            <li><strong>Stok Kritis:</strong> ≤ 5 satuan (akan ditandai merah)</li>
                            <li><strong>Stok Rendah:</strong> 6-10 satuan (akan ditandai kuning)</li>
                            <li><strong>Stok Aman:</strong> > 10 satuan (akan ditandai hijau)</li>
                        </ul>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
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
                                Simpan Obat
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
                    <i class="fas fa-lightbulb mr-1"></i>
                    Tips Pengelolaan Obat
                </h3>
            </div>
            <div class="card-body">
                <div class="callout callout-info">
                    <h6><i class="fas fa-shield-alt"></i> Keamanan Obat</h6>
                    <p class="text-sm">Pastikan nama obat tepat dan sesuai dengan kemasan resmi untuk menghindari kesalahan pemberian.</p>
                </div>

                <div class="callout callout-warning">
                    <h6><i class="fas fa-thermometer-half"></i> Penyimpanan</h6>
                    <p class="text-sm">Perhatikan kondisi penyimpanan obat sesuai dengan petunjuk pada kemasan (suhu ruang, kulkas, dll).</p>
                </div>

                <div class="callout callout-success">
                    <h6><i class="fas fa-calendar-check"></i> Tanggal Kadaluarsa</h6>
                    <p class="text-sm">Selalu cek tanggal kadaluarsa saat menerima stok baru dan terapkan sistem FIFO (First In, First Out).</p>
                </div>
            </div>
        </div>

        <!-- Recent Medicines -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Obat Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                @php
                    $recentMedicines = \App\Models\Medicine::latest()->take(5)->get();
                @endphp
                @forelse($recentMedicines as $medicine)
                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                    <div>
                        <strong>{{ $medicine->name }}</strong><br>
                        <small class="text-muted">
                            {{ ucfirst($medicine->type) }} | 
                            Stok: {{ $medicine->stock }} {{ $medicine->unit }}
                        </small>
                    </div>
                    <span class="badge badge-{{ $medicine->stock_status == 'critical' ? 'danger' : ($medicine->stock_status == 'low' ? 'warning' : 'success') }}">
                        {{ $medicine->stock }}
                    </span>
                </div>
                @empty
                <div class="text-center p-3 text-muted">
                    Belum ada obat dalam inventory
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

.text-sm {
    font-size: 0.875rem;
}
</style>

@push('scripts')
<script>
// Auto-update unit based on type selection
$('#type').on('change', function() {
    var type = $(this).val();
    var unitSelect = $('#unit');
    
    // Clear current selection
    unitSelect.val('');
    
    // Suggest appropriate unit based on type
    var suggestions = {
        'tablet': 'tablet',
        'kapsul': 'kapsul', 
        'sirup': 'botol',
        'serbuk': 'sachet',
        'salep': 'tube',
        'tetes': 'botol',
        'antiseptik': 'botol',
        'herbal': 'sachet',
        'suspensi': 'botol',
        'injeksi': 'vial'
    };
    
    if (suggestions[type]) {
        unitSelect.val(suggestions[type]);
    }
});

// Format price input
$('#price').on('input', function() {
    var value = $(this).val();
    if (value) {
        // Remove any non-numeric characters except decimal
        value = value.replace(/[^\d.]/g, '');
        // Ensure only one decimal point
        var parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }
        $(this).val(value);
    }
});
</script>
@endpush
@endsection