@extends('layouts.app')

@section('title', 'Data Obat')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Data Obat</li>
@endsection

@section('content')
<!-- Header & Actions -->
<div class="row mb-3">
    <div class="col-md-6">
        <h1 class="h3 mb-0">
            <i class="fas fa-pills text-success mr-2"></i>
            Data Obat & Inventory
        </h1>
        <p class="text-muted">Kelola stok dan informasi obat-obatan</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('medicines.create') }}" class="btn btn-success">
            <i class="fas fa-plus mr-1"></i>
            Tambah Obat Baru
        </a>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-pills"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Obat</span>
                <span class="info-box-number">{{ $medicines->total() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Stok Kritis</span>
                <span class="info-box-number">{{ \App\Models\Medicine::where('stock', '<=', 5)->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-battery-quarter"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Stok Rendah</span>
                <span class="info-box-number">{{ \App\Models\Medicine::whereBetween('stock', [6, 10])->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Stok Aman</span>
                <span class="info-box-number">{{ \App\Models\Medicine::where('stock', '>', 10)->count() }}</span>
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
        <form method="GET" action="{{ route('medicines.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search">Cari Obat</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Nama obat atau deskripsi...">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="type">Jenis Obat</label>
                        <select class="form-control" id="type" name="type">
                            <option value="">Semua Jenis</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="stock_status">Status Stok</label>
                        <select class="form-control" id="stock_status" name="stock_status">
                            <option value="">Semua Status</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                            <option value="medium" {{ request('stock_status') == 'medium' ? 'selected' : '' }}>Stok Sedang</option>
                            <option value="good" {{ request('stock_status') == 'good' ? 'selected' : '' }}>Stok Baik</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="sort">Urutkan</label>
                        <select class="form-control" id="sort" name="sort">
                            <option value="">Terbaru</option>
                            <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stok Terendah</option>
                        </select>
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

<!-- Medicines Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list mr-1"></i>
            Daftar Obat ({{ $medicines->total() }} obat)
        </h3>
        <div class="card-tools">
            <a href="{{ route('medicines.index', ['sort' => 'stock_asc']) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Prioritas Stok Rendah
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        @if($medicines->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Nama Obat</th>
                        <th>Jenis</th>
                        <th>Stok</th>
                        <th>Status Stok</th>
                        <th>Harga</th>
                        <th>Satuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $medicine)
                    <tr class="{{ $medicine->stock <= 5 ? 'table-danger' : ($medicine->stock <= 10 ? 'table-warning' : '') }}">
                        <td>
                            <div>
                                <strong>{{ $medicine->name }}</strong>
                                @if($medicine->description)
                                    <br><small class="text-muted">{{ Str::limit($medicine->description, 50) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($medicine->type) }}</span>
                        </td>
                        <td>
                            <span class="h5 mb-0 
                                {{ $medicine->stock <= 5 ? 'text-danger' : ($medicine->stock <= 10 ? 'text-warning' : 'text-success') }}">
                                {{ $medicine->stock }}
                            </span>
                        </td>
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
                        <td>{{ $medicine->formatted_price }}</td>
                        <td>{{ $medicine->unit }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('medicines.show', $medicine) }}" 
                                   class="btn btn-info btn-sm" 
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('medicines.edit', $medicine) }}" 
                                   class="btn btn-warning btn-sm" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-success btn-sm" 
                                        title="Quick Stock Update"
                                        data-toggle="modal" 
                                        data-target="#stockModal"
                                        data-id="{{ $medicine->id }}"
                                        data-name="{{ $medicine->name }}"
                                        data-stock="{{ $medicine->stock }}"
                                        data-unit="{{ $medicine->unit }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="card-footer">
            {{ $medicines->withQueryString()->links() }}
        </div>
        
        @else
        <div class="text-center py-5">
            <i class="fas fa-pills fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Tidak ada data obat</h5>
            @if(request()->filled('search'))
                <p class="text-muted">Tidak ditemukan obat dengan kata kunci "{{ request('search') }}"</p>
                <a href="{{ route('medicines.index') }}" class="btn btn-secondary">Tampilkan Semua Obat</a>
            @else
                <p class="text-muted">Belum ada obat dalam inventory</p>
                <a href="{{ route('medicines.create') }}" class="btn btn-success">
                    <i class="fas fa-plus mr-1"></i>
                    Tambah Obat Pertama
                </a>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Quick Stock Update Modal -->
<div class="modal fade" id="stockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="fas fa-plus mr-1"></i>
                    Update Stok Obat
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="stockForm">
                    <input type="hidden" id="medicineId">
                    
                    <div class="alert alert-info">
                        <strong id="medicineName"></strong><br>
                        Stok saat ini: <strong id="currentStock"></strong> <span id="unit"></span>
                    </div>
                    
                    <div class="form-group">
                        <label>Operasi Stok</label>
                        <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                            <label class="btn btn-outline-success active flex-fill">
                                <input type="radio" name="operation" value="add" checked> Tambah Stok
                            </label>
                            <label class="btn btn-outline-warning flex-fill">
                                <input type="radio" name="operation" value="subtract"> Kurangi Stok
                            </label>
                            <label class="btn btn-outline-info flex-fill">
                                <input type="radio" name="operation" value="set"> Set Stok
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="stockAmount">Jumlah</label>
                        <input type="number" class="form-control" id="stockAmount" min="0" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="updateStock()">
                    <i class="fas fa-save mr-1"></i>
                    Update Stok
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#stockModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var name = button.data('name');
    var stock = button.data('stock');
    var unit = button.data('unit');
    
    $('#medicineId').val(id);
    $('#medicineName').text(name);
    $('#currentStock').text(stock);
    $('#unit').text(unit);
    $('#stockAmount').val('');
});

function updateStock() {
    var id = $('#medicineId').val();
    var operation = $('input[name="operation"]:checked').val();
    var amount = $('#stockAmount').val();
    
    if (!amount || amount < 0) {
        toastr.error('Masukkan jumlah yang valid!');
        return;
    }
    
    $.post('{{ route("medicines.index") }}/' + id + '/quick-stock', {
        _token: '{{ csrf_token() }}',
        operation: operation,
        stock: amount
    }).done(function(response) {
        if (response.success) {
            toastr.success(response.message);
            location.reload();
        } else {
            toastr.error(response.message);
        }
    }).fail(function() {
        toastr.error('❌ Terjadi kesalahan saat update stok');
    });
}
</script>
@endpush