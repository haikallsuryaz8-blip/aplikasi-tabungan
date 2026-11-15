<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TargetTabungan;
use App\Models\TransaksiCelengan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. User Utama: Haikal
        $user = User::updateOrCreate(
            ['email' => 'haikal@tabungin.test'],
            [
                'name' => 'Haikal Suryadarmansyah',
                'username' => 'haikal',
                'password' => Hash::make('haikal123'),
            ]
        );

        // 2. Data Target Impian (Savings Goals)
        $target1 = TargetTabungan::create([
            'user_id' => $user->id,
            'nama_target' => 'Laptop Asus ROG Gaming',
            'kategori' => 'Elektronik',
            'target_nominal' => 15000000,
            'saldo_terkumpul' => 4500000,
            'tenggat_waktu' => now()->addDays(75)->toDateString(),
            'ikon' => 'fa-laptop-code',
            'warna' => 'blue',
            'status' => 'aktif',
            'catatan' => 'Buat coding Laravel, game development, dan render video UKK.',
        ]);

        $target2 = TargetTabungan::create([
            'user_id' => $user->id,
            'nama_target' => 'Motor Impian Yamaha NMAX',
            'kategori' => 'Kendaraan',
            'target_nominal' => 32000000,
            'saldo_terkumpul' => 8000000,
            'tenggat_waktu' => now()->addMonths(8)->toDateString(),
            'ikon' => 'fa-motorcycle',
            'warna' => 'purple',
            'status' => 'aktif',
            'catatan' => 'DP motor baru buat mobilitas kuliah & kerja nanti.',
        ]);

        $target3 = TargetTabungan::create([
            'user_id' => $user->id,
            'nama_target' => 'Dana Darurat Mandiri',
            'kategori' => 'Investasi',
            'target_nominal' => 10000000,
            'saldo_terkumpul' => 6200000,
            'tenggat_waktu' => now()->addMonths(6)->toDateString(),
            'ikon' => 'fa-shield-halved',
            'warna' => 'emerald',
            'status' => 'aktif',
            'catatan' => 'Dana proteksi minimal 3 bulan pengeluaran.',
        ]);

        $target4 = TargetTabungan::create([
            'user_id' => $user->id,
            'nama_target' => 'Liburan Akhir Tahun',
            'kategori' => 'Liburan',
            'target_nominal' => 3000000,
            'saldo_terkumpul' => 3000000,
            'tenggat_waktu' => now()->addDays(30)->toDateString(),
            'ikon' => 'fa-plane-departure',
            'warna' => 'amber',
            'status' => 'tercapai',
            'catatan' => 'Target tercapai! Siap jalan-jalan akhir tahun.',
        ]);

        // 3. Mutasi Transaksi Celengan
        $mutasi = [
            // Target 1: Laptop
            [
                'id_target' => $target1->id_target,
                'jenis_transaksi' => 'SETOR',
                'nominal' => 2500000,
                'sumber_dana' => 'Gaji / Honor',
                'tanggal_transaksi' => now()->subDays(25)->toDateString(),
                'keterangan' => 'Nabung pertama dari honor project awal bulan',
                'saldo_sebelum' => 0,
                'saldo_sesudah' => 2500000,
            ],
            [
                'id_target' => $target1->id_target,
                'jenis_transaksi' => 'SETOR',
                'nominal' => 2000000,
                'sumber_dana' => 'Freelance',
                'tanggal_transaksi' => now()->subDays(10)->toDateString(),
                'keterangan' => 'Nabung dari bonus freelance pembuatan website',
                'saldo_sebelum' => 2500000,
                'saldo_sesudah' => 4500000,
            ],
            // Target 2: Motor
            [
                'id_target' => $target2->id_target,
                'jenis_transaksi' => 'SETOR',
                'nominal' => 5000000,
                'sumber_dana' => 'Tabungan Lama',
                'tanggal_transaksi' => now()->subDays(40)->toDateString(),
                'keterangan' => 'Pindahan tabungan lama',
                'saldo_sebelum' => 0,
                'saldo_sesudah' => 5000000,
            ],
            [
                'id_target' => $target2->id_target,
                'jenis_transaksi' => 'SETOR',
                'nominal' => 3000000,
                'sumber_dana' => 'Gaji / Honor',
                'tanggal_transaksi' => now()->subDays(15)->toDateString(),
                'keterangan' => 'Alokasi bulanan motor',
                'saldo_sebelum' => 5000000,
                'saldo_sesudah' => 8000000,
            ],
            // Target 3: Dana Darurat
            [
                'id_target' => $target3->id_target,
                'jenis_transaksi' => 'SETOR',
                'nominal' => 7000000,
                'sumber_dana' => 'THR & Hadiah',
                'tanggal_transaksi' => now()->subDays(30)->toDateString(),
                'keterangan' => 'Setoran dana darurat awal',
                'saldo_sebelum' => 0,
                'saldo_sesudah' => 7000000,
            ],
            [
                'id_target' => $target3->id_target,
                'jenis_transaksi' => 'TARIK',
                'nominal' => 800000,
                'sumber_dana' => 'Kebutuhan Darurat',
                'tanggal_transaksi' => now()->subDays(8)->toDateString(),
                'keterangan' => 'Servis HP mendesak',
                'saldo_sebelum' => 7000000,
                'saldo_sesudah' => 6200000,
            ],
            // Target 4: Liburan
            [
                'id_target' => $target4->id_target,
                'jenis_transaksi' => 'SETOR',
                'nominal' => 3000000,
                'sumber_dana' => 'Uang Saku',
                'tanggal_transaksi' => now()->subDays(5)->toDateString(),
                'keterangan' => 'Pelunasan budget tiket dan penginapan',
                'saldo_sebelum' => 0,
                'saldo_sesudah' => 3000000,
            ],
        ];

        foreach ($mutasi as $item) {
            TransaksiCelengan::create($item);
        }
    }
}
