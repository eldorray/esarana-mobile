<?php

namespace Database\Seeders;

use App\Models\BahanHabisPakai;
use App\Models\Inventaris;
use App\Models\Kategori;
use App\Models\Laporan;
use App\Models\Lokasi;
use App\Models\Peminjaman;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // == Roles & Permissions ==
        $permissions = [
            'view_dashboard', 'manage_inventaris', 'manage_lokasi', 'manage_ruangan',
            'manage_peminjaman', 'approve_peminjaman', 'manage_laporan',
            'manage_bahan', 'manage_kategori', 'manage_users', 'manage_roles',
            'export_laporan',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $admin = Role::firstOrCreate(['name' => 'administrator']);
        $admin->syncPermissions($permissions);

        $supervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $supervisor->syncPermissions([
            'view_dashboard', 'manage_inventaris', 'manage_lokasi', 'manage_ruangan',
            'manage_peminjaman', 'approve_peminjaman', 'manage_laporan', 'manage_bahan',
            'export_laporan',
        ]);

        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->syncPermissions([
            'view_dashboard', 'manage_inventaris', 'manage_peminjaman', 'manage_laporan', 'manage_bahan',
        ]);

        // == Users ==
        $adminUser = User::create([
            'name' => 'Admin eSarana',
            'email' => 'admin@esarana.id',
            'password' => Hash::make('password'),
        ]);
        $adminUser->assignRole('administrator');

        $supervisorUser = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@esarana.id',
            'password' => Hash::make('password'),
        ]);
        $supervisorUser->assignRole('supervisor');

        $staffUser = User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@esarana.id',
            'password' => Hash::make('password'),
        ]);
        $staffUser->assignRole('staff');

        $staffUser2 = User::create([
            'name' => 'Andi Permana',
            'email' => 'andi@esarana.id',
            'password' => Hash::make('password'),
        ]);
        $staffUser2->assignRole('staff');

        // == Kategori ==
        $elektronik = Kategori::create(['nama' => 'Elektronik', 'deskripsi' => 'Perangkat elektronik dan IT', 'icon' => 'devices', 'is_active' => true]);
        $perangkat = Kategori::create(['nama' => 'Perangkat Kantor', 'deskripsi' => 'Alat tulis dan perlengkapan kantor', 'icon' => 'print', 'is_active' => true]);
        $mebel = Kategori::create(['nama' => 'Mebel & Furniture', 'deskripsi' => 'Meja, kursi, lemari', 'icon' => 'chair', 'is_active' => true]);
        $kendaraan = Kategori::create(['nama' => 'Kendaraan', 'deskripsi' => 'Kendaraan operasional', 'icon' => 'directions_car', 'is_active' => true]);
        $atk = Kategori::create(['nama' => 'ATK', 'deskripsi' => 'Alat Tulis Kantor', 'icon' => 'edit_note', 'is_active' => true]);
        Kategori::create(['nama' => 'Laboratorium', 'deskripsi' => 'Peralatan laboratorium', 'icon' => 'science', 'is_active' => true]);

        // == Lokasi ==
        $gedungA = Lokasi::create([
            'nama' => 'Gedung Utama Jakarta',
            'alamat' => 'Jl. Merdeka No. 1, Jakarta Pusat',
            'tipe' => 'utama',
            'status' => 'operasional',
        ]);

        $gudang = Lokasi::create([
            'nama' => 'Gudang Logistik Tangerang',
            'alamat' => 'Jl. Industri No. 45, Tangerang',
            'tipe' => 'gudang',
            'status' => 'operasional',
        ]);

        $kantor = Lokasi::create([
            'nama' => 'Kantor Cabang Bandung',
            'alamat' => 'Jl. Asia Afrika No. 20, Bandung',
            'tipe' => 'satelit',
            'status' => 'operasional',
        ]);

        Lokasi::create([
            'nama' => 'Workshop Bekasi',
            'alamat' => 'Jl. Workshop No. 8, Bekasi',
            'tipe' => 'satelit',
            'status' => 'renovasi',
        ]);

        // == Ruangan ==
        $rMeeting = Ruangan::create(['lokasi_id' => $gedungA->id, 'nama' => 'Ruang Meeting Utama', 'lantai' => '2', 'kapasitas' => 20, 'status' => 'aktif']);
        $rServer = Ruangan::create(['lokasi_id' => $gedungA->id, 'nama' => 'Ruang Server', 'lantai' => '1', 'kapasitas' => 5, 'status' => 'aktif']);
        $rKerja = Ruangan::create(['lokasi_id' => $gedungA->id, 'nama' => 'Ruang Kerja Tim IT', 'lantai' => '3', 'kapasitas' => 30, 'status' => 'aktif']);
        $rGudang = Ruangan::create(['lokasi_id' => $gudang->id, 'nama' => 'Gudang Elektronik', 'lantai' => '1', 'kapasitas' => 50, 'status' => 'aktif']);
        $rBandung = Ruangan::create(['lokasi_id' => $kantor->id, 'nama' => 'Ruang Operasional', 'lantai' => '1', 'kapasitas' => 15, 'status' => 'aktif']);
        Ruangan::create(['lokasi_id' => $gudang->id, 'nama' => 'Area Staging', 'lantai' => '1', 'kapasitas' => 30, 'status' => 'aktif']);

        // == Inventaris ==
        $macbook = Inventaris::create([
            'nama' => 'MacBook Pro M2 14"',
            'kode' => 'INV-ELK-2024-001',
            'kategori_id' => $elektronik->id,
            'ruangan_id' => $rKerja->id,
            'deskripsi' => 'MacBook Pro M2, RAM 16GB, SSD 512GB',
            'serial_number' => 'C02ZG9ABCD1E',
            'kondisi' => 'baik',
            'status' => 'tersedia',
            'nilai_aset' => 32000000,
            'tanggal_perolehan' => '2024-03-15',
        ]);

        $printer = Inventaris::create([
            'nama' => 'Printer Canon PIXMA G3020',
            'kode' => 'INV-PRK-2024-002',
            'kategori_id' => $perangkat->id,
            'ruangan_id' => $rKerja->id,
            'deskripsi' => 'Printer All-in-One inkjet wireless',
            'serial_number' => 'CNXL78901',
            'kondisi' => 'baik',
            'status' => 'tersedia',
            'nilai_aset' => 3500000,
            'tanggal_perolehan' => '2024-01-10',
        ]);

        $proyektor = Inventaris::create([
            'nama' => 'Proyektor Epson EB-X51',
            'kode' => 'INV-ELK-2024-003',
            'kategori_id' => $elektronik->id,
            'ruangan_id' => $rMeeting->id,
            'deskripsi' => 'Proyektor 3800 lumens, XGA resolution',
            'serial_number' => 'EPXF4523K',
            'kondisi' => 'baik',
            'status' => 'dipinjam',
            'nilai_aset' => 8500000,
            'tanggal_perolehan' => '2023-11-20',
        ]);

        Inventaris::create([
            'nama' => 'Kursi Ergonomis Herman Miller',
            'kode' => 'INV-MBL-2024-004',
            'kategori_id' => $mebel->id,
            'ruangan_id' => $rKerja->id,
            'deskripsi' => 'Kursi ergonomis premium, mesh back, lumbar support',
            'serial_number' => 'HM-AERON-2024',
            'kondisi' => 'baik',
            'status' => 'tersedia',
            'nilai_aset' => 18000000,
            'tanggal_perolehan' => '2024-06-01',
        ]);

        Inventaris::create([
            'nama' => 'Server Rack Dell PowerEdge',
            'kode' => 'INV-ELK-2024-005',
            'kategori_id' => $elektronik->id,
            'ruangan_id' => $rServer->id,
            'deskripsi' => 'Server rack 2U, Xeon E-2388G, 64GB ECC RAM',
            'serial_number' => 'DELL-PE-8834',
            'kondisi' => 'baik',
            'status' => 'tersedia',
            'nilai_aset' => 85000000,
            'tanggal_perolehan' => '2024-02-01',
        ]);

        Inventaris::create([
            'nama' => 'Monitor Samsung 27" 4K',
            'kode' => 'INV-ELK-2024-006',
            'kategori_id' => $elektronik->id,
            'ruangan_id' => $rKerja->id,
            'serial_number' => 'SMNG-U27-4K',
            'kondisi' => 'baik',
            'status' => 'tersedia',
            'nilai_aset' => 6500000,
            'tanggal_perolehan' => '2024-04-15',
        ]);

        Inventaris::create([
            'nama' => 'Toyota Avanza 2023',
            'kode' => 'INV-KND-2024-007',
            'kategori_id' => $kendaraan->id,
            'ruangan_id' => $rGudang->id,
            'deskripsi' => 'Kendaraan operasional, plat B 1234 XYZ',
            'kondisi' => 'baik',
            'status' => 'tersedia',
            'nilai_aset' => 250000000,
            'tanggal_perolehan' => '2023-08-01',
        ]);

        Inventaris::create([
            'nama' => 'Whiteboard Magnetic 120x240',
            'kode' => 'INV-PRK-2024-008',
            'kategori_id' => $perangkat->id,
            'ruangan_id' => $rMeeting->id,
            'kondisi' => 'cukup',
            'status' => 'maintenance',
            'nilai_aset' => 1200000,
            'tanggal_perolehan' => '2022-03-01',
        ]);

        // == Bahan Habis Pakai ==
        BahanHabisPakai::create([
            'nama' => 'Kertas HVS A4 80gr',
            'kode' => 'BHP-ATK-001',
            'kategori_id' => $atk->id,
            'ruangan_id' => $rGudang->id,
            'stok' => 150,
            'satuan' => 'rim',
            'stok_minimum' => 20,
            'harga_satuan' => 55000,
        ]);

        BahanHabisPakai::create([
            'nama' => 'Toner Printer Canon GI-71',
            'kode' => 'BHP-ATK-002',
            'kategori_id' => $atk->id,
            'ruangan_id' => $rGudang->id,
            'stok' => 3,
            'satuan' => 'botol',
            'stok_minimum' => 5,
            'harga_satuan' => 125000,
        ]);

        BahanHabisPakai::create([
            'nama' => 'Spidol Whiteboard Snowman',
            'kode' => 'BHP-ATK-003',
            'kategori_id' => $atk->id,
            'ruangan_id' => $rGudang->id,
            'stok' => 2,
            'satuan' => 'lusin',
            'stok_minimum' => 3,
            'harga_satuan' => 85000,
        ]);

        BahanHabisPakai::create([
            'nama' => 'Hand Sanitizer 500ml',
            'kode' => 'BHP-KBR-004',
            'kategori_id' => $atk->id,
            'ruangan_id' => $rGudang->id,
            'stok' => 25,
            'satuan' => 'botol',
            'stok_minimum' => 10,
            'harga_satuan' => 35000,
        ]);

        BahanHabisPakai::create([
            'nama' => 'Baterai AA Alkaline',
            'kode' => 'BHP-ELK-005',
            'kategori_id' => $elektronik->id,
            'ruangan_id' => $rGudang->id,
            'stok' => 4,
            'satuan' => 'pack',
            'stok_minimum' => 5,
            'harga_satuan' => 45000,
        ]);

        // == Peminjaman ==
        Peminjaman::create([
            'user_id' => $staffUser->id,
            'inventaris_id' => $proyektor->id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => now()->addDays(3),
            'status' => 'aktif',
            'catatan' => 'Untuk presentasi klien di Meeting Room',
        ]);

        Peminjaman::create([
            'user_id' => $staffUser2->id,
            'inventaris_id' => $macbook->id,
            'tanggal_pinjam' => now()->subDays(5),
            'tanggal_kembali_rencana' => now()->subDays(1),
            'status' => 'aktif',
            'catatan' => 'Kebutuhan development project',
        ]);

        Peminjaman::create([
            'user_id' => $supervisorUser->id,
            'inventaris_id' => $printer->id,
            'tanggal_pinjam' => now()->subDays(10),
            'tanggal_kembali_rencana' => now()->subDays(3),
            'tanggal_kembali_aktual' => now()->subDays(4),
            'status' => 'selesai',
            'catatan' => 'Print laporan bulanan',
        ]);

        // == Laporan ==
        Laporan::create([
            'user_id' => $staffUser->id,
            'tipe' => 'kerusakan',
            'aset_lokasi' => 'AC Split Ruang Meeting Lt.2',
            'kategori_laporan' => 'kerusakan',
            'deskripsi' => 'AC tidak mendinginkan, terdengar suara berdengung. Sudah dicoba restart tapi masih sama.',
            'status' => 'baru',
        ]);

        Laporan::create([
            'user_id' => $staffUser2->id,
            'tipe' => 'permintaan',
            'aset_lokasi' => 'Ruang Kerja Tim IT Lt.3',
            'kategori_laporan' => 'permintaan',
            'deskripsi' => 'Membutuhkan tambahan monitor untuk workstation baru. Saat ini ada 2 developer tanpa monitor eksternal.',
            'status' => 'proses',
        ]);

        Laporan::create([
            'user_id' => $supervisorUser->id,
            'tipe' => 'kerusakan',
            'aset_lokasi' => 'Whiteboard Meeting Room',
            'kategori_laporan' => 'kerusakan',
            'deskripsi' => 'Permukaan whiteboard sudah tidak bersih saat dihapus. Perlu penggantian.',
            'status' => 'selesai',
        ]);
    }
}
