<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Barang;
use App\Models\DetailPembelian;
use App\Models\DetailPenjualan;
use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('1234'),
        ]);

        Barang::factory(25)->create();

        Karyawan::factory(12)->create();

        Absensi::factory(100)->create();

        Pelanggan::factory(50)->create();

        Supplier::factory(10)->create();

        Penjualan::factory(300)->create();

        DetailPenjualan::factory(100)->create();

        Pembelian::factory(169)->create();

        DetailPembelian::factory(169)->create();
    }
}
