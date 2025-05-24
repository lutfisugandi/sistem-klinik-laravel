<?php
// Medical App Emoji Standards
// Use these consistently across all controllers and views

// ===== SUCCESS NOTIFICATIONS (GREEN) =====
// General Success
'🎉' => 'Major achievement/completion',
'✅' => 'Standard success operation',
'💾' => 'Data saved successfully',
'🔄' => 'Process completed/updated',

// Patient Management
'🏥' => 'Patient registered/admitted',
'👤' => 'Patient profile updated',
'📋' => 'Patient record created',
'📝' => 'Form submitted successfully',

// Medical Procedures
'🩺' => 'Medical examination completed',
'💉' => 'Vital signs recorded',
'🧬' => 'Diagnosis saved',
'💊' => 'Prescription created',
'🔬' => 'Lab/test completed',

// ===== WARNING NOTIFICATIONS (ORANGE) =====
// Duplicates & Conflicts
'📞' => 'Phone number duplicate',
'👥' => 'Duplicate patient data',
'⚠️' => 'General warning/attention needed',
'📊' => 'Data needs verification',

// Medical Alerts
'🔋' => 'Low stock warning',
'⏰' => 'Time-sensitive alert',
'🚨' => 'Medical attention required',
'📈' => 'Abnormal vital signs',

// ===== ERROR NOTIFICATIONS (RED) =====
// System Errors
'❌' => 'Operation failed',
'🚫' => 'Access denied/forbidden',
'💥' => 'System error/crash',
'🛑' => 'Process stopped/cancelled',
'⛔' => 'Validation failed',

// Data Errors
'🔒' => 'Cannot delete (has dependencies)',
'📉' => 'Data integrity issue',
'🗃️' => 'Database connection error',

// ===== INFO NOTIFICATIONS (BLUE) =====
// General Information
'ℹ️' => 'General information',
'📢' => 'System announcement',
'💡' => 'Tips and suggestions',
'🔔' => 'Reminder/notification',

// Medical Information
'📄' => 'Report generated',
'📈' => 'Statistics updated',
'🗂️' => 'Records archived',
'📤' => 'Data exported',

// ===== PROGRESS & STATUS =====
// Processing States
'⏳' => 'Please wait/processing',
'🔄' => 'Synchronizing/updating',
'📤' => 'Uploading/sending',
'📥' => 'Downloading/receiving',

// ===== MEDICAL WORKFLOW SPECIFIC =====
// Patient Journey
'🚪' => 'Patient check-in',
'🩺' => 'Medical examination',
'💉' => 'Vital signs/injection',
'🧬' => 'Diagnosis phase',
'💊' => 'Prescription/medication',
'🏠' => 'Patient discharge',

// Staff Actions
'👨‍⚕️' => 'Doctor action',
'👩‍⚕️' => 'Nurse action',
'👩‍💼' => 'Admin action',
'👨‍🔬' => 'Pharmacy action',

// ===== USAGE EXAMPLES =====

// Patient Management Examples:
// Success: "🎉 Pasien berhasil didaftarkan! Nomor: P20241201001"
// Warning: "📞 Nomor telepon sudah terdaftar atas nama: John Doe"
// Error: "❌ Gagal menyimpan data pasien. Silakan coba lagi."

// Medical Records Examples:
// Success: "💉 Vital signs berhasil dicatat untuk Ahmad Fauzi"
// Success: "🧬 Diagnosis berhasil disimpan! Status: Menunggu resep"
// Success: "💊 Resep obat berhasil dibuat untuk Siti Aminah"

// Medicine Management Examples:
// Success: "💊 Obat baru berhasil ditambahkan ke inventory"
// Warning: "🔋 Stok Paracetamol 500mg tinggal 5 tablet"
// Info: "📈 Statistik obat diperbarui"

// System Messages Examples:
// Info: "💾 Data berhasil dibackup otomatis"
// Info: "🔔 Update sistem tersedia! Klik untuk info lebih lanjut"
// Success: "🔄 Sinkronisasi database selesai"