<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Prescription;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Indonesian medical staff
        $users = [
            [
                'name' => 'Dewi Sartika, A.Md.Keb',
                'email' => 'pendaftaran@klinik.com',
                'password' => Hash::make('password'),
                'role' => 'pendaftaran',
                'phone' => '08123456789'
            ],
            [
                'name' => 'dr. Budi Rahardjo, Sp.PD',
                'email' => 'dokter@klinik.com',
                'password' => Hash::make('password'),
                'role' => 'dokter',
                'phone' => '08234567890'
            ],
            [
                'name' => 'Ns. Siti Aminah, S.Kep',
                'email' => 'perawat@klinik.com',
                'password' => Hash::make('password'),
                'role' => 'perawat',
                'phone' => '08345678901'
            ],
            [
                'name' => 'Apt. Agus Salim, S.Farm',
                'email' => 'apoteker@klinik.com',
                'password' => Hash::make('password'),
                'role' => 'apoteker',
                'phone' => '08456789012'
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Create Indonesian medicines (common in Indonesia)
        $medicines = [
            [
                'name' => 'Paracetamol 500mg (Sanmol)',
                'type' => 'tablet',
                'description' => 'Obat penurun demam dan pereda nyeri',
                'stock' => 150,
                'price' => 500,
                'unit' => 'tablet'
            ],
            [
                'name' => 'Amoxicillin 500mg (Yusimox)',
                'type' => 'kapsul',
                'description' => 'Antibiotik untuk infeksi bakteri',
                'stock' => 80,
                'price' => 1200,
                'unit' => 'kapsul'
            ],
            [
                'name' => 'ORS (Oralit) Pharos',
                'type' => 'serbuk',
                'description' => 'Garam rehidrasi oral untuk diare',
                'stock' => 50,
                'price' => 2500,
                'unit' => 'sachet'
            ],
            [
                'name' => 'Betadine Solution 15ml',
                'type' => 'antiseptik',
                'description' => 'Antiseptik untuk luka luar',
                'stock' => 25,
                'price' => 18000,
                'unit' => 'botol'
            ],
            [
                'name' => 'Vitamin C 100mg (Redoxon)',
                'type' => 'tablet',
                'description' => 'Suplemen vitamin C',
                'stock' => 120,
                'price' => 350,
                'unit' => 'tablet'
            ],
            [
                'name' => 'Antasida DOEN',
                'type' => 'tablet',
                'description' => 'Obat maag dan dispepsia',
                'stock' => 75,
                'price' => 750,
                'unit' => 'tablet'
            ],
            [
                'name' => 'CTM 4mg (Chlorpheniramine)',
                'type' => 'tablet',
                'description' => 'Antihistamin untuk alergi',
                'stock' => 100,
                'price' => 400,
                'unit' => 'tablet'
            ],
            [
                'name' => 'Ibuprofen 400mg (Proris)',
                'type' => 'tablet',
                'description' => 'Anti inflamasi dan pereda nyeri',
                'stock' => 60,
                'price' => 1000,
                'unit' => 'tablet'
            ],
            [
                'name' => 'Bisolvon Syrup 60ml',
                'type' => 'sirup',
                'description' => 'Obat batuk berdahak',
                'stock' => 30,
                'price' => 25000,
                'unit' => 'botol'
            ],
            [
                'name' => 'Promag Tablet',
                'type' => 'tablet',
                'description' => 'Obat sakit maag',
                'stock' => 90,
                'price' => 800,
                'unit' => 'tablet'
            ],
            [
                'name' => 'Tolak Angin Cair',
                'type' => 'herbal',
                'description' => 'Obat herbal masuk angin',
                'stock' => 40,
                'price' => 3500,
                'unit' => 'sachet'
            ],
            [
                'name' => 'Mylanta Suspension',
                'type' => 'suspensi',
                'description' => 'Obat maag dan kembung',
                'stock' => 35,
                'price' => 15000,
                'unit' => 'botol'
            ]
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }

        $patients = [
            [
                'name' => 'Bambang Sutrisno',
                'birth_date' => '1985-03-15',
                'gender' => 'L',
                'phone' => '08123456001',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat'
            ],
            [
                'name' => 'Siti Nurhaliza',
                'birth_date' => '1990-07-22',
                'gender' => 'P',
                'phone' => '08123456002',
                'address' => 'Jl. Gatot Subroto No. 45, Jakarta Selatan'
            ],
            [
                'name' => 'Ahmad Fauzi',
                'birth_date' => '1978-11-08',
                'gender' => 'L',
                'phone' => '08123456003',
                'address' => 'Jl. Thamrin No. 67, Jakarta Pusat'
            ],
            [
                'name' => 'Rina Wati',
                'birth_date' => '1995-02-14',
                'gender' => 'P',
                'phone' => '08123456004',
                'address' => 'Jl. Kemang Raya No. 89, Jakarta Selatan'
            ],
            [
                'name' => 'Dedi Kurniawan',
                'birth_date' => '1982-09-30',
                'gender' => 'L',
                'phone' => '08123456005',
                'address' => 'Jl. Pancoran No. 12, Jakarta Selatan'
            ],
            [
                'name' => 'Maya Sari',
                'birth_date' => '1988-12-05',
                'gender' => 'P',
                'phone' => '08123456006',
                'address' => 'Jl. Cikini Raya No. 34, Jakarta Pusat'
            ],
            [
                'name' => 'Hendra Wijaya',
                'birth_date' => '1975-06-18',
                'gender' => 'L',
                'phone' => '08123456007',
                'address' => 'Jl. Senayan No. 56, Jakarta Pusat'
            ],
            [
                'name' => 'Indah Permata',
                'birth_date' => '1993-04-25',
                'gender' => 'P',
                'phone' => '08123456008',
                'address' => 'Jl. Kuningan No. 78, Jakarta Selatan'
            ]
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);
        }

        $medicalCases = [
            [
                'patient_id' => 1, // Bambang Sutrisno
                'status' => 'completed',
                'weight' => 70.5,
                'blood_pressure' => '130/85',
                'temperature' => 38.2,
                'heart_rate' => 88,
                'complaints' => 'Demam tinggi sejak 2 hari, sakit kepala, badan lemas, tidak nafsu makan',
                'diagnosis' => 'Demam Berdarah Dengue (DBD) Grade I',
                'notes' => 'Pasien disarankan banyak minum air putih, istirahat cukup, kontrol rutin',
                'registered_at' => now()->subDays(2),
                'vitals_at' => now()->subDays(2)->addMinutes(15),
                'diagnosed_at' => now()->subDays(2)->addMinutes(30),
                'prescribed_at' => now()->subDays(2)->addMinutes(45),
                'completed_at' => now()->subDays(2)->addHour(),
                'medicines' => [
                    ['medicine_id' => 1, 'dosage' => '3x1', 'instructions' => 'Sesudah makan', 'quantity' => 9],
                    ['medicine_id' => 5, 'dosage' => '2x1', 'instructions' => 'Pagi dan sore', 'quantity' => 6]
                ]
            ],
            [
                'patient_id' => 2, // Siti Nurhaliza
                'status' => 'completed',
                'weight' => 58.0,
                'blood_pressure' => '110/70',
                'temperature' => 36.8,
                'heart_rate' => 72,
                'complaints' => 'Batuk berdahak sudah 1 minggu, dahak berwarna kuning, kadang sesak napas',
                'diagnosis' => 'Infeksi Saluran Pernapasan Atas (ISPA)',
                'notes' => 'Hindari makanan dingin, perbanyak minum air hangat',
                'registered_at' => now()->subDays(1),
                'vitals_at' => now()->subDays(1)->addMinutes(10),
                'diagnosed_at' => now()->subDays(1)->addMinutes(25),
                'prescribed_at' => now()->subDays(1)->addMinutes(40),
                'completed_at' => now()->subDays(1)->addMinutes(55),
                'medicines' => [
                    ['medicine_id' => 2, 'dosage' => '3x1', 'instructions' => 'Sesudah makan', 'quantity' => 15],
                    ['medicine_id' => 9, 'dosage' => '3x1 cth', 'instructions' => 'Sesudah makan', 'quantity' => 1]
                ]
            ],
            [
                'patient_id' => 3, // Ahmad Fauzi
                'status' => 'completed',
                'weight' => 75.2,
                'blood_pressure' => '140/90',
                'temperature' => 36.5,
                'heart_rate' => 85,
                'complaints' => 'Sakit maag kambuh, perut perih, mual, muntah setelah makan pedas',
                'diagnosis' => 'Gastritis Akut',
                'notes' => 'Hindari makanan pedas, asam, dan berlemak. Makan teratur',
                'registered_at' => now()->subHours(3),
                'vitals_at' => now()->subHours(3)->addMinutes(12),
                'diagnosed_at' => now()->subHours(3)->addMinutes(28),
                'prescribed_at' => now()->subHours(3)->addMinutes(45),
                'completed_at' => now()->subHours(3)->addHour(),
                'medicines' => [
                    ['medicine_id' => 6, 'dosage' => '3x1', 'instructions' => 'Sebelum makan', 'quantity' => 9],
                    ['medicine_id' => 12, 'dosage' => '3x1 cth', 'instructions' => 'Saat perut perih', 'quantity' => 1]
                ]
            ],
            [
                'patient_id' => 4, // Rina Wati
                'status' => 'diagnosed',
                'weight' => 52.5,
                'blood_pressure' => '100/70',
                'temperature' => 37.1,
                'heart_rate' => 78,
                'complaints' => 'Gatal-gatal di tangan dan kaki, bentol-bentol merah, muncul setelah makan seafood',
                'diagnosis' => 'Dermatitis Alergi (Alergi Makanan)',
                'notes' => 'Hindari seafood, catat makanan pemicu alergi',
                'registered_at' => now()->subMinutes(45),
                'vitals_at' => now()->subMinutes(30),
                'diagnosed_at' => now()->subMinutes(15),
                'medicines' => [
                    ['medicine_id' => 7, 'dosage' => '3x1', 'instructions' => 'Sesudah makan', 'quantity' => 9],
                    ['medicine_id' => 1, 'dosage' => '3x1', 'instructions' => 'Jika demam', 'quantity' => 6]
                ]
            ],
            [
                'patient_id' => 5, // Dedi Kurniawan
                'status' => 'vitals_checked',
                'weight' => 82.0,
                'blood_pressure' => '150/95',
                'temperature' => 36.7,
                'heart_rate' => 92,
                'complaints' => 'Pusing, tengkuk kaku, mata berkunang-kunang, sering terjadi pagi hari',
                'registered_at' => now()->subMinutes(20),
                'vitals_at' => now()->subMinutes(10),
            ]
        ];

        foreach ($medicalCases as $case) {
            $medicines = $case['medicines'] ?? [];
            unset($case['medicines']);
            
            $record = MedicalRecord::create($case);
            
            // Add prescriptions if medicines exist
            foreach ($medicines as $medicine) {
                Prescription::create([
                    'medical_record_id' => $record->id,
                    'medicine_id' => $medicine['medicine_id'],
                    'dosage' => $medicine['dosage'],
                    'instructions' => $medicine['instructions'],
                    'quantity' => $medicine['quantity']
                ]);
            }
        }



        echo "✅ Seeder completed!\n";
        echo "📧 Login credentials:\n";
        echo "   Pendaftaran: pendaftaran@klinik.com / password\n";
        echo "   Dokter: dokter@klinik.com / password\n";
        echo "   Perawat: perawat@klinik.com / password\n";
        echo "   Apoteker: apoteker@klinik.com / password\n";
    }
}