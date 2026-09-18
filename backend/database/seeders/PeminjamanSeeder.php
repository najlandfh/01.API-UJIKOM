<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PeminjamSeeder extends Seeder
{
    /**
     * Menjalankan seeder untuk mengisi data dummy peminjam.
     */
    public function run(): void
    {
        $peminjams = [
            [
                'name' => 'Rian Setiawan',
                'email' => 'rian.setiawan@example.com',
                'no_hp' => '081234560001',
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti.aminah@example.com',
                'no_hp' => '081234560002',
            ],
            [
                'name' => 'Eka Pratama',
                'email' => 'eka.pratama@example.com',
                'no_hp' => '081234560003',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'no_hp' => '081234560004',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@example.com',
                'no_hp' => '081234560005',
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@example.com',
                'no_hp' => '081234560006',
            ],
            [
                'name' => 'Putri Handayani',
                'email' => 'putri.handayani@example.com',
                'no_hp' => '081234560007',
            ],
        ];

        foreach ($peminjams as $data) {
            User::updateOrCreate(
                ['email' => $data['email']], // hindari duplikat kalau seeder dijalankan berkali-kali
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'), // password default untuk semua dummy user
                    'role' => 'peminjam',
                    'no_hp' => $data['no_hp'],
                ]
            );
        }

        $this->command->info('Berhasil menambahkan ' . count($peminjams) . ' data peminjam dummy.');
    }
}