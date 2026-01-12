<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Inventory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bidanpro.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create Demo Bidan
        $bidan = User::create([
            'name' => 'Bidan Sari Dewi',
            'email' => 'bidan@bidanpro.com',
            'phone' => '081234567891',
            'password' => Hash::make('password'),
            'role' => 'bidan',
            'is_active' => true,
        ]);

        // Create Demo Staff
        User::create([
            'name' => 'Staff Admin',
            'email' => 'staff@bidanpro.com',
            'phone' => '081234567892',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'is_active' => true,
            'created_by' => $bidan->id,
        ]);

        // Create Demo Patients
        $patients = [
            [
                'nik' => '3201234567890001',
                'name' => 'Ani Suryani',
                'gender' => 'P',
                'birth_date' => '1995-03-15',
                'birth_place' => 'Bandung',
                'address' => 'Jl. Sudirman No. 123, Bandung',
                'phone' => '081234567801',
                'password' => Hash::make('password'),
                'blood_type' => 'A',
                'bpjs_number' => '0001234567890',
                'husband_name' => 'Budi Santoso',
            ],
            [
                'nik' => '3201234567890002',
                'name' => 'Dewi Lestari',
                'gender' => 'P',
                'birth_date' => '1992-07-22',
                'birth_place' => 'Jakarta',
                'address' => 'Jl. Merdeka No. 45, Jakarta',
                'phone' => '081234567802',
                'password' => Hash::make('password'),
                'blood_type' => 'B',
                'bpjs_number' => '0001234567891',
                'husband_name' => 'Andi Pratama',
            ],
            [
                'nik' => '3201234567890003',
                'name' => 'Rina Oktavia',
                'gender' => 'P',
                'birth_date' => '1998-10-05',
                'birth_place' => 'Surabaya',
                'address' => 'Jl. Pahlawan No. 78, Surabaya',
                'phone' => '081234567803',
                'password' => Hash::make('password'),
                'blood_type' => 'O',
            ],
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);
        }

        // Create Demo Services
        $services = [
            ['code' => 'ANC-01', 'name' => 'Pemeriksaan Kehamilan', 'category' => 'ANC', 'price' => 150000],
            ['code' => 'ANC-02', 'name' => 'USG Kehamilan', 'category' => 'ANC', 'price' => 250000],
            ['code' => 'KB-01', 'name' => 'Suntik KB 1 Bulan', 'category' => 'KB', 'price' => 50000],
            ['code' => 'KB-02', 'name' => 'Suntik KB 3 Bulan', 'category' => 'KB', 'price' => 75000],
            ['code' => 'KB-03', 'name' => 'Pasang IUD', 'category' => 'KB', 'price' => 200000],
            ['code' => 'IMN-01', 'name' => 'Imunisasi BCG', 'category' => 'Imunisasi', 'price' => 50000],
            ['code' => 'IMN-02', 'name' => 'Imunisasi DPT', 'category' => 'Imunisasi', 'price' => 75000],
            ['code' => 'IMN-03', 'name' => 'Imunisasi Polio', 'category' => 'Imunisasi', 'price' => 50000],
            ['code' => 'UMM-01', 'name' => 'Pemeriksaan Umum', 'category' => 'Umum', 'price' => 75000],
            ['code' => 'PNC-01', 'name' => 'Pemeriksaan Pasca Melahirkan', 'category' => 'PNC', 'price' => 150000],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Create Demo Inventory
        $inventories = [
            ['code' => 'OBT-001', 'name' => 'Paracetamol 500mg', 'category' => 'obat', 'unit' => 'strip', 'stock' => 100, 'min_stock' => 20, 'buy_price' => 5000, 'sell_price' => 8000],
            ['code' => 'OBT-002', 'name' => 'Amoxicillin 500mg', 'category' => 'obat', 'unit' => 'strip', 'stock' => 50, 'min_stock' => 15, 'buy_price' => 15000, 'sell_price' => 22000],
            ['code' => 'VIT-001', 'name' => 'Vitamin C 1000mg', 'category' => 'vitamin', 'unit' => 'botol', 'stock' => 30, 'min_stock' => 10, 'buy_price' => 25000, 'sell_price' => 35000],
            ['code' => 'VIT-002', 'name' => 'Tablet Fe (Zat Besi)', 'category' => 'vitamin', 'unit' => 'strip', 'stock' => 80, 'min_stock' => 25, 'buy_price' => 8000, 'sell_price' => 12000],
            ['code' => 'ALK-001', 'name' => 'Sarung Tangan Latex', 'category' => 'alkes', 'unit' => 'box', 'stock' => 5, 'min_stock' => 10, 'buy_price' => 50000, 'sell_price' => 65000],
            ['code' => 'ALK-002', 'name' => 'Masker Medis', 'category' => 'alkes', 'unit' => 'box', 'stock' => 8, 'min_stock' => 15, 'buy_price' => 45000, 'sell_price' => 55000],
        ];

        foreach ($inventories as $inventory) {
            Inventory::create($inventory);
        }

        $this->command->info('Demo data berhasil ditambahkan!');
        $this->command->info('');
        $this->command->info('Admin Login:');
        $this->command->info('Super Admin: admin@bidanpro.com / password');
        $this->command->info('Bidan: bidan@bidanpro.com / password');
        $this->command->info('Staff: staff@bidanpro.com / password');
        $this->command->info('');
        $this->command->info('Pasien Login (HP / password):');
        $this->command->info('Ani: 081234567801 / password');
        $this->command->info('Dewi: 081234567802 / password');
        $this->command->info('Rina: 081234567803 / password');
    }
}
