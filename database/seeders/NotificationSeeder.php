<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users (admin, bidan, staff)
        $users = User::all();

        $notificationTypes = [
            [
                'type' => 'new_patient',
                'title' => 'Pasien Baru Terdaftar',
                'message' => 'Pasien baru "Siti Aminah" telah terdaftar di sistem.',
                'link' => '/admin/patients',
            ],
            [
                'type' => 'new_queue',
                'title' => 'Antrean Baru',
                'message' => 'Ada pasien baru yang mengambil nomor antrean untuk layanan Pemeriksaan Kehamilan.',
                'link' => '/admin/queues',
            ],
            [
                'type' => 'low_stock',
                'title' => 'Stok Obat Menipis',
                'message' => 'Stok "Vitamin Prenatal" tinggal 5 unit. Segera lakukan pengisian ulang.',
                'link' => '/admin/inventory',
            ],
            [
                'type' => 'payment_received',
                'title' => 'Pembayaran Diterima',
                'message' => 'Pembayaran invoice #INV-2026-0045 sebesar Rp 250.000 telah diterima.',
                'link' => '/admin/billing',
            ],
            [
                'type' => 'medical_record',
                'title' => 'Rekam Medis Baru',
                'message' => 'Rekam medis baru untuk pasien "Dewi Lestari" telah dibuat.',
                'link' => '/admin/medical-records',
            ],
            [
                'type' => 'new_patient',
                'title' => 'Pasien Baru Terdaftar',
                'message' => 'Pasien baru "Rina Wati" telah terdaftar melalui aplikasi mobile.',
                'link' => '/admin/patients',
            ],
            [
                'type' => 'low_stock',
                'title' => 'Stok Obat Habis',
                'message' => 'Stok "Asam Folat 400mcg" sudah habis! Segera lakukan pemesanan.',
                'link' => '/admin/inventory',
            ],
        ];

        foreach ($users as $user) {
            // Create 5-7 random notifications for each user
            $selectedNotifications = collect($notificationTypes)->random(rand(5, 7));

            foreach ($selectedNotifications as $index => $notifData) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => $notifData['type'],
                    'title' => $notifData['title'],
                    'message' => $notifData['message'],
                    'link' => $notifData['link'],
                    'read_at' => $index > 3 ? now()->subMinutes(rand(10, 60)) : null, // Some read, some unread
                    'created_at' => now()->subMinutes(rand(5, 180)),
                ]);
            }
        }

        $this->command->info('Sample notifications created successfully!');
    }
}
