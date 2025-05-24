<?php
// app/Http/Controllers/MedicineController.php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of medicines.
     */
    public function index(Request $request)
    {
        $query = Medicine::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->where('stock', '<=', 10);
                    break;
                case 'medium':
                    $query->whereBetween('stock', [11, 50]);
                    break;
                case 'good':
                    $query->where('stock', '>', 50);
                    break;
            }
        }

        // Sort by stock status (low stock first if requested)
        if ($request->filled('sort') && $request->sort === 'stock_asc') {
            $query->orderBy('stock', 'asc');
        } else {
            $query->latest();
        }

        $medicines = $query->paginate(15);

        // Get types for filter dropdown
        $types = Medicine::distinct()->pluck('type')->filter()->sort();

        return view('medicines.index', compact('medicines', 'types'));
    }

    /**
     * Show the form for creating a new medicine.
     */
    public function create()
    {
        return view('medicines.create');
    }

    /**
     * Store a newly created medicine.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:medicines,name',
                'type' => 'required|string|max:100',
                'description' => 'nullable|string|max:500',
                'stock' => 'required|integer|min:0',
                'price' => 'required|numeric|min:0|max:9999999.99',
                'unit' => 'required|string|max:50',
            ], [
                'name.required' => 'Nama obat wajib diisi',
                'name.unique' => 'Nama obat sudah ada dalam sistem',
                'type.required' => 'Jenis obat wajib diisi',
                'stock.required' => 'Jumlah stok wajib diisi',
                'stock.min' => 'Stok tidak boleh negatif',
                'price.required' => 'Harga obat wajib diisi',
                'price.min' => 'Harga tidak boleh negatif',
                'unit.required' => 'Satuan obat wajib diisi',
            ]);

            $medicine = Medicine::create($validated);

            // Check stock level for warning
            $message = '💊 Obat baru berhasil ditambahkan ke inventory!';
            $type = 'success';
            
            if ($medicine->stock <= 10) {
                $message .= ' ⚠️ Perhatian: Stok awal tergolong rendah.';
                $type = 'warning';
            }

            return redirect()
                ->route('medicines.show', $medicine)
                ->with($type, $message);
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Terjadi kesalahan saat menyimpan data obat. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified medicine.
     */
    public function show(Medicine $medicine)
    {
        // Get related prescriptions count
        $prescriptionCount = $medicine->prescriptions()->count();
        
        // Get recent prescriptions
        $recentPrescriptions = $medicine->prescriptions()
            ->with(['medicalRecord.patient'])
            ->latest()
            ->take(10)
            ->get();

        return view('medicines.show', compact('medicine', 'prescriptionCount', 'recentPrescriptions'));
    }

    /**
     * Show the form for editing the medicine.
     */
    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    /**
     * Update the specified medicine.
     */
    public function update(Request $request, Medicine $medicine)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:medicines,name,' . $medicine->id,
                'type' => 'required|string|max:100',
                'description' => 'nullable|string|max:500',
                'stock' => 'required|integer|min:0',
                'price' => 'required|numeric|min:0|max:9999999.99',
                'unit' => 'required|string|max:50',
            ], [
                'name.required' => 'Nama obat wajib diisi',
                'name.unique' => 'Nama obat sudah ada dalam sistem',
                'type.required' => 'Jenis obat wajib diisi',
                'stock.required' => 'Jumlah stok wajib diisi',
                'stock.min' => 'Stok tidak boleh negatif',
                'price.required' => 'Harga obat wajib diisi',
                'price.min' => 'Harga tidak boleh negatif',
                'unit.required' => 'Satuan obat wajib diisi',
            ]);

            $oldStock = $medicine->stock;
            $medicine->update($validated);

            // Determine message based on stock changes
            $message = '✅ Data obat berhasil diperbarui!';
            $type = 'success';

            if ($validated['stock'] != $oldStock) {
                if ($validated['stock'] > $oldStock) {
                    $message = '📦 Stok obat berhasil ditambah dari ' . $oldStock . ' menjadi ' . $validated['stock'] . ' ' . $medicine->unit;
                } else {
                    $message = '📉 Stok obat dikurangi dari ' . $oldStock . ' menjadi ' . $validated['stock'] . ' ' . $medicine->unit;
                }
            }

            if ($medicine->stock <= 5) {
                $message .= ' 🚨 Stok kritis! Segera lakukan restock.';
                $type = 'warning';
            } elseif ($medicine->stock <= 10) {
                $message .= ' 🔋 Stok rendah, perlu diperhatikan.';
                $type = 'warning';
            }

            return redirect()
                ->route('medicines.show', $medicine)
                ->with($type, $message);
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Terjadi kesalahan saat memperbarui data obat.');
        }
    }

    /**
     * Remove the specified medicine.
     */
    public function destroy(Medicine $medicine)
    {
        try {
            // Check if medicine is used in prescriptions
            if ($medicine->prescriptions()->count() > 0) {
                return redirect()
                    ->route('medicines.index')
                    ->with('error', '🔒 Obat tidak dapat dihapus karena sudah digunakan dalam ' . $medicine->prescriptions()->count() . ' resep');
            }

            $medicineName = $medicine->name;
            $medicine->delete();

            return redirect()
                ->route('medicines.index')
                ->with('success', '🗑️ Obat "' . $medicineName . '" berhasil dihapus dari inventory');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('medicines.index')
                ->with('error', '❌ Terjadi kesalahan saat menghapus data obat.');
        }
    }

    /**
     * Quick stock update (AJAX endpoint)
     */
    public function quickStockUpdate(Request $request, Medicine $medicine)
    {
        try {
            $validated = $request->validate([
                'stock' => 'required|integer|min:0',
                'operation' => 'required|in:add,subtract,set'
            ]);

            $oldStock = $medicine->stock;
            $newStock = $validated['stock'];

            switch ($validated['operation']) {
                case 'add':
                    $medicine->stock += $newStock;
                    break;
                case 'subtract':
                    $medicine->stock = max(0, $medicine->stock - $newStock);
                    break;
                case 'set':
                    $medicine->stock = $newStock;
                    break;
            }

            $medicine->save();

            $message = '📦 Stok ' . $medicine->name . ' berhasil diperbarui dari ' . $oldStock . ' menjadi ' . $medicine->stock . ' ' . $medicine->unit;

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_stock' => $medicine->stock,
                'stock_status' => $medicine->stock_status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Gagal memperbarui stok obat'
            ], 500);
        }
    }
}