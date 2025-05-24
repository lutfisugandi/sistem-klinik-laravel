@extends('layouts.app')

@section('title', 'Buat Resep Obat')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('prescriptions.index') }}">Resep Obat</a></li>
<li class="breadcrumb-item active">{{ $record->patient->name }}</li>
@endsection

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-md-12">
        <h1 class="h3 mb-0">
            <i class="fas fa-prescription text-success mr-2"></i>
            {{ $existingPrescriptions->count() > 0 ? 'Edit' : 'Buat' }} Resep Obat
        </h1>
        <p class="text-muted">
            Pasien: <strong>{{ $record->patient->name }}</strong> | 
            No. Kunjungan: <strong>{{ $record->visit_number }}</strong> | 
            {{ $record->diagnosed_at->format('d F Y, H:i') }}
        </p>
    </div>
</div>

<!-- Patient Info & Diagnosis -->
<div class="row">
    <!-- Patient Information -->
    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user mr-1"></i>
                    Informasi Pasien
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-circle mx-auto mb-2" style="width: 80px; height: 80px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user fa-2x text-white"></i>
                    </div>
                    <h4 class="mb-0">{{ $record->patient->name }}</h4>
                    <p class="text-muted">{{ $record->patient->patient_number }}</p>
                </div>

                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Umur:</strong></td>
                        <td>{{ $record->patient->age }} tahun</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kelamin:</strong></td>
                        <td>
                            <span class="badge badge-{{ $record->patient->gender == 'L' ? 'primary' : 'pink' }}">
                                {{ $record->patient->gender_display }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Berat Badan:</strong></td>
                        <td>{{ $record->weight }} kg</td>
                    </tr>
                    <tr>
                        <td><strong>No. Telepon:</strong></td>
                        <td>{{ $record->patient->phone }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Diagnosis Information -->
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-stethoscope mr-1"></i>
                    Diagnosis
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Keluhan:</strong>
                    <p class="text-muted mb-2">{{ $record->complaints }}</p>
                </div>
                <div class="mb-2">
                    <strong>Diagnosis:</strong>
                    <p class="text-primary mb-2">{{ $record->diagnosis }}</p>
                </div>
                @if($record->notes)
                <div class="mb-2">
                    <strong>Catatan Dokter:</strong>
                    <p class="text-muted mb-0">{{ $record->notes }}</p>
                </div>
                @endif
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
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="medicineSearch" placeholder="Nama obat...">
                    <div class="input-group-append">
                        <button class="btn btn-info" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div id="searchResults" class="search-results"></div>
            </div>
        </div>
    </div>

    <!-- Prescription Form -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-prescription mr-1"></i>
                    Form Resep Obat
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" onclick="addMedicineRow()">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah Obat
                    </button>
                </div>
            </div>
            <form action="{{ route('prescriptions.update', $record) }}" method="POST" id="prescriptionForm">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="medicinesTable">
                            <thead class="thead-light">
                                <tr>
                                    <th width="30%">Obat</th>
                                    <th width="15%">Dosis</th>
                                    <th width="10%">Jumlah</th>
                                    <th width="25%">Instruksi Penggunaan</th>
                                    <th width="15%">Harga</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="medicinesTableBody">
                                @if($existingPrescriptions->count() > 0)
                                    @foreach($existingPrescriptions as $index => $prescription)
                                    <tr class="medicine-row">
                                        <td>
                                            <select class="form-control medicine-select" name="medicines[{{ $index }}][medicine_id]" required>
                                                <option value="">Pilih Obat</option>
                                                @foreach($medicines as $medicine)
                                                    <option value="{{ $medicine->id }}" 
                                                            data-stock="{{ $medicine->stock }}" 
                                                            data-unit="{{ $medicine->unit }}"
                                                            data-price="{{ $medicine->price }}"
                                                            {{ $prescription->medicine_id == $medicine->id ? 'selected' : '' }}>
                                                        {{ $medicine->name }} (Stok: {{ $medicine->stock }} {{ $medicine->unit }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="stock-info text-muted"></small>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="medicines[{{ $index }}][dosage]" 
                                                   value="{{ $prescription->dosage }}" placeholder="3x1" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control quantity-input" name="medicines[{{ $index }}][quantity]" 
                                                   value="{{ $prescription->quantity }}" min="1" max="1000" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="medicines[{{ $index }}][instructions]" 
                                                   value="{{ $prescription->instructions }}" placeholder="Sesudah makan" required>
                                        </td>
                                        <td>
                                            <span class="price-display">Rp 0</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeMedicineRow(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr class="medicine-row">
                                        <td>
                                            <select class="form-control medicine-select" name="medicines[0][medicine_id]" required>
                                                <option value="">Pilih Obat</option>
                                                @foreach($medicines as $medicine)
                                                    <option value="{{ $medicine->id }}" 
                                                            data-stock="{{ $medicine->stock }}" 
                                                            data-unit="{{ $medicine->unit }}"
                                                            data-price="{{ $medicine->price }}">
                                                        {{ $medicine->name }} (Stok: {{ $medicine->stock }} {{ $medicine->unit }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="stock-info text-muted"></small>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="medicines[0][dosage]" placeholder="3x1" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control quantity-input" name="medicines[0][quantity]" min="1" max="1000" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="medicines[0][instructions]" placeholder="Sesudah makan" required>
                                        </td>
                                        <td>
                                            <span class="price-display">Rp 0</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeMedicineRow(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr class="table-info">
                                    <th colspan="4">Total Biaya Obat:</th>
                                    <th><span id="totalPrice">Rp 0</span></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Common Prescriptions Templates -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6>Template Resep Umum (Klik untuk menambah):</h6>
                            <div class="btn-group-toggle" data-toggle="buttons">
                                <button type="button" class="btn btn-outline-primary btn-sm mr-1 mb-1" onclick="addCommonPrescription('fever')">
                                    <i class="fas fa-thermometer-half mr-1"></i>Demam
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm mr-1 mb-1" onclick="addCommonPrescription('cough')">
                                    <i class="fas fa-lungs mr-1"></i>Batuk
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm mr-1 mb-1" onclick="addCommonPrescription('gastritis')">
                                    <i class="fas fa-stomach mr-1"></i>Maag
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm mr-1 mb-1" onclick="addCommonPrescription('hypertension')">
                                    <i class="fas fa-heart mr-1"></i>Hipertensi
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm mr-1 mb-1" onclick="addCommonPrescription('allergy')">
                                    <i class="fas fa-allergies mr-1"></i>Alergi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('prescriptions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-warning" onclick="resetForm()">
                                <i class="fas fa-undo mr-1"></i>
                                Reset
                            </button>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save mr-1"></i>
                                Selesaikan Resep
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Pharmacy Reference -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-book-medical mr-1"></i>
                    Referensi Dosis & Instruksi
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6><i class="fas fa-clock text-primary"></i> Frekuensi Dosis</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• <strong>1x1:</strong> Sekali sehari</li>
                            <li>• <strong>2x1:</strong> Dua kali sehari</li>
                            <li>• <strong>3x1:</strong> Tiga kali sehari</li>
                            <li>• <strong>4x1:</strong> Empat kali sehari</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-utensils text-warning"></i> Waktu Pemberian</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• <strong>Sebelum makan:</strong> 30-60 menit sebelum</li>
                            <li>• <strong>Sesudah makan:</strong> 30 menit setelah</li>
                            <li>• <strong>Saat makan:</strong> Bersamaan dengan makanan</li>
                            <li>• <strong>Sebelum tidur:</strong> 30 menit sebelum tidur</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-exclamation-triangle text-danger"></i> Perhatian Khusus</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• <strong>Antibiotik:</strong> Harus dihabiskan</li>
                            <li>• <strong>Antihistamin:</strong> Dapat menyebabkan kantuk</li>
                            <li>• <strong>Steroid:</strong> Tidak boleh dihentikan mendadak</li>
                            <li>• <strong>Insulin:</strong> Simpan di kulkas</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-baby text-info"></i> Dosis Pediatrik</h6>
                        <ul class="list-unstyled text-sm">
                            <li>• <strong>Paracetamol:</strong> 10-15 mg/kg/dosis</li>
                            <li>• <strong>Amoxicillin:</strong> 25-50 mg/kg/hari</li>
                            <li>• <strong>ORS:</strong> 75 ml/kg dalam 4 jam</li>
                            <li>• <strong>Vitamin C:</strong> 30-50 mg/hari</li>
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

.search-results {
    max-height: 200px;
    overflow-y: auto;
}

.search-result-item {
    padding: 8px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
}

.search-result-item:hover {
    background-color: #f8f9fa;
}

.medicine-row select option[data-stock="0"] {
    color: #dc3545;
    background-color: #f8d7da;
}

.price-display {
    font-weight: bold;
    color: #28a745;
}
</style>

@push('scripts')
<script>
let medicineRowCount = {{ $existingPrescriptions->count() > 0 ? $existingPrescriptions->count() : 1 }};

// Initialize existing rows
$(document).ready(function() {
    $('.medicine-row').each(function() {
        updateMedicineInfo($(this).find('.medicine-select'));
        calculateRowPrice($(this));
    });
    calculateTotalPrice();
});

// Add new medicine row
function addMedicineRow() {
    const tableBody = $('#medicinesTableBody');
    const newRow = `
        <tr class="medicine-row">
            <td>
                <select class="form-control medicine-select" name="medicines[${medicineRowCount}][medicine_id]" required>
                    <option value="">Pilih Obat</option>
                    @foreach($medicines as $medicine)
                        <option value="{{ $medicine->id }}" 
                                data-stock="{{ $medicine->stock }}" 
                                data-unit="{{ $medicine->unit }}"
                                data-price="{{ $medicine->price }}">
                            {{ $medicine->name }} (Stok: {{ $medicine->stock }} {{ $medicine->unit }})
                        </option>
                    @endforeach
                </select>
                <small class="stock-info text-muted"></small>
            </td>
            <td>
                <input type="text" class="form-control" name="medicines[${medicineRowCount}][dosage]" placeholder="3x1" required>
            </td>
            <td>
                <input type="number" class="form-control quantity-input" name="medicines[${medicineRowCount}][quantity]" min="1" max="1000" required>
            </td>
            <td>
                <input type="text" class="form-control" name="medicines[${medicineRowCount}][instructions]" placeholder="Sesudah makan" required>
            </td>
            <td>
                <span class="price-display">Rp 0</span>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeMedicineRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
    
    tableBody.append(newRow);
    medicineRowCount++;
    
    // Initialize new row
    const newRowElement = tableBody.find('tr:last');
    newRowElement.find('.medicine-select').on('change', function() {
        updateMedicineInfo($(this));
        calculateRowPrice(newRowElement);
        calculateTotalPrice();
    });
    
    newRowElement.find('.quantity-input').on('input', function() {
        calculateRowPrice(newRowElement);
        calculateTotalPrice();
        validateStock(newRowElement);
    });
}

// Remove medicine row
function removeMedicineRow(button) {
    if ($('.medicine-row').length > 1) {
        $(button).closest('tr').remove();
        calculateTotalPrice();
    } else {
        toastr.warning('Minimal satu obat harus ada dalam resep');
    }
}

// Update medicine information
function updateMedicineInfo(selectElement) {
    const selectedOption = selectElement.find('option:selected');
    const row = selectElement.closest('tr');
    const stockInfo = row.find('.stock-info');
    
    if (selectedOption.val()) {
        const stock = selectedOption.data('stock');
        const unit = selectedOption.data('unit');
        
        stockInfo.html(`Stok tersedia: <strong>${stock} ${unit}</strong>`);
        
        if (stock <= 5) {
            stockInfo.addClass('text-danger').html(`<i class="fas fa-exclamation-triangle"></i> Stok kritis: <strong>${stock} ${unit}</strong>`);
        } else if (stock <= 10) {
            stockInfo.addClass('text-warning').html(`<i class="fas fa-exclamation-circle"></i> Stok rendah: <strong>${stock} ${unit}</strong>`);
        }
        
        // Set max quantity
        row.find('.quantity-input').attr('max', stock);
    } else {
        stockInfo.html('');
        row.find('.quantity-input').attr('max', 1000);
    }
}

// Calculate row price
function calculateRowPrice(row) {
    const select = row.find('.medicine-select');
    const selectedOption = select.find('option:selected');
    const quantity = parseInt(row.find('.quantity-input').val()) || 0;
    const priceDisplay = row.find('.price-display');
    
    if (selectedOption.val() && quantity > 0) {
        const price = parseFloat(selectedOption.data('price')) || 0;
        const total = price * quantity;
        priceDisplay.text('Rp ' + total.toLocaleString('id-ID'));
    } else {
        priceDisplay.text('Rp 0');
    }
}

// Calculate total price
function calculateTotalPrice() {
    let total = 0;
    $('.medicine-row').each(function() {
        const select = $(this).find('.medicine-select');
        const selectedOption = select.find('option:selected');
        const quantity = parseInt($(this).find('.quantity-input').val()) || 0;
        
        if (selectedOption.val() && quantity > 0) {
            const price = parseFloat(selectedOption.data('price')) || 0;
            total += price * quantity;
        }
    });
    
    $('#totalPrice').text('Rp ' + total.toLocaleString('id-ID'));
}

// Validate stock
function validateStock(row) {
    const select = row.find('.medicine-select');
    const selectedOption = select.find('option:selected');
    const quantity = parseInt(row.find('.quantity-input').val()) || 0;
    
    if (selectedOption.val()) {
        const stock = parseInt(selectedOption.data('stock')) || 0;
        const quantityInput = row.find('.quantity-input');
        
        if (quantity > stock) {
            quantityInput.addClass('is-invalid');
            toastr.error(`Jumlah melebihi stok tersedia (${stock})`);
        } else {
            quantityInput.removeClass('is-invalid');
        }
    }
}

// Event handlers
$(document).on('change', '.medicine-select', function() {
    updateMedicineInfo($(this));
    calculateRowPrice($(this).closest('tr'));
    calculateTotalPrice();
});

$(document).on('input', '.quantity-input', function() {
    const row = $(this).closest('tr');
    calculateRowPrice(row);
    calculateTotalPrice();
    validateStock(row);
});

// Medicine search
$('#medicineSearch').on('input', function() {
    const query = $(this).val().toLowerCase();
    const results = $('#searchResults');
    
    if (query.length < 2) {
        results.empty();
        return;
    }
    
    const medicines = @json($medicines->toArray());
    const filtered = medicines.filter(medicine => 
        medicine.name.toLowerCase().includes(query)
    ).slice(0, 5);
    
    let html = '';
    filtered.forEach(medicine => {
        html += `
            <div class="search-result-item" onclick="selectMedicine(${medicine.id}, '${medicine.name}')">
                <strong>${medicine.name}</strong><br>
                <small class="text-muted">Stok: ${medicine.stock} ${medicine.unit} | ${medicine.formatted_price}</small>
            </div>
        `;
    });
    
    results.html(html);
});

// Select medicine from search
function selectMedicine(medicineId, medicineName) {
    // Add to last empty row or create new row
    let targetRow = $('.medicine-row').last();
    if (targetRow.find('.medicine-select').val() !== '') {
        addMedicineRow();
        targetRow = $('.medicine-row').last();
    }
    
    targetRow.find('.medicine-select').val(medicineId).trigger('change');
    $('#medicineSearch').val('');
    $('#searchResults').empty();
}

// Common prescriptions
function addCommonPrescription(type) {
    const templates = {
        fever: [
            { medicine: 'Paracetamol 500mg (Sanmol)', dosage: '3x1', instructions: 'Sesudah makan bila demam', quantity: 6 }
        ],
        cough: [
            { medicine: 'Bisolvon Syrup 60ml', dosage: '3x1 cth', instructions: 'Sesudah makan', quantity: 1 }
        ],
        gastritis: [
            { medicine: 'Antasida DOEN', dosage: '3x1', instructions: 'Sebelum makan', quantity: 9 },
            { medicine: 'Promag Tablet', dosage: '3x1', instructions: 'Saat perut perih', quantity: 6 }
        ],
        hypertension: [
            { medicine: 'Amlodipine 5mg', dosage: '1x1', instructions: 'Pagi hari sesudah makan', quantity: 30 }
        ],
        allergy: [
            { medicine: 'CTM 4mg (Chlorpheniramine)', dosage: '3x1', instructions: 'Sesudah makan', quantity: 9 }
        ]
    };
    
    const template = templates[type];
    if (template) {
        template.forEach(item => {
            // Find medicine option
            const medicineOption = $(`option:contains("${item.medicine}")`).first();
            if (medicineOption.length) {
                addMedicineRow();
                const lastRow = $('.medicine-row').last();
                lastRow.find('.medicine-select').val(medicineOption.val()).trigger('change');
                lastRow.find('input[name*="[dosage]"]').val(item.dosage);
                lastRow.find('input[name*="[instructions]"]').val(item.instructions);
                lastRow.find('input[name*="[quantity]"]').val(item.quantity);
                calculateRowPrice(lastRow);
            }
        });
        calculateTotalPrice();
    }
}

// Form validation
$('#prescriptionForm').on('submit', function(e) {
    let isValid = true;
    
    $('.medicine-row').each(function() {
        const medicineSelect = $(this).find('.medicine-select');
        const dosage = $(this).find('input[name*="[dosage]"]');
        const quantity = $(this).find('input[name*="[quantity]"]');
        const instructions = $(this).find('input[name*="[instructions]"]');
        
        if (!medicineSelect.val() || !dosage.val() || !quantity.val() || !instructions.val()) {
            isValid = false;
            $(this).find('.form-control').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                }
            });
        }
        
        // Check stock
        const selectedOption = medicineSelect.find('option:selected');
        if (selectedOption.val()) {
            const stock = parseInt(selectedOption.data('stock')) || 0;
            const reqQuantity = parseInt(quantity.val()) || 0;
            
            if (reqQuantity > stock) {
                isValid = false;
                quantity.addClass('is-invalid');
                toastr.error(`Stok ${selectedOption.text()} tidak mencukupi`);
            }
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        toastr.error('Harap lengkapi semua field dan periksa stok obat');
    }
});

// Reset form
function resetForm() {
    if (confirm('Yakin ingin mereset form? Semua data akan hilang.')) {
        $('#medicinesTableBody').empty();
        medicineRowCount = 0;
        addMedicineRow();
        calculateTotalPrice();
    }
}
</script>
@endpush
@endsection