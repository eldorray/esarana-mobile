<?php

use App\Livewire\Auth\Login;
use App\Livewire\Cari;
use App\Livewire\Dashboard;
use App\Livewire\Lokasi\LokasiIndex;
use App\Livewire\Lokasi\LokasiShow;
use App\Livewire\Lokasi\LokasiCreate;
use App\Livewire\Lokasi\RuanganShow;
use App\Livewire\Inventaris\InventarisIndex;
use App\Livewire\Inventaris\InventarisCreate;
use App\Livewire\Inventaris\InventarisEdit;
use App\Livewire\Inventaris\InventarisShow;
use App\Livewire\Peminjaman\PeminjamanIndex;
use App\Livewire\Peminjaman\PeminjamanCreate;
use App\Livewire\BahanHabisPakai\BahanIndex;
use App\Livewire\BahanHabisPakai\BahanShow;
use App\Livewire\Inventaris\BahanHabisPakaiIndex;
use App\Livewire\Inventaris\BahanCreate;
use App\Livewire\Inventaris\BahanEdit;
use App\Livewire\Inventaris\BahanShow as InventarisBahanShow;
use App\Livewire\Laporan\LaporanIndex;
use App\Livewire\Laporan\LaporanCreate;
use App\Livewire\Laporan\LaporanShow;
use App\Livewire\Laporan\LaporanPublik;
use App\Livewire\MasterData\MasterDataIndex;
use App\Livewire\MasterData\KategoriIndex;
use App\Livewire\MasterData\UserIndex;
use App\Livewire\MasterData\RoleIndex;
use App\Livewire\MasterData\AuditLogIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// PUBLIC ROUTES (tanpa login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

// Lapor publik — bisa diakses tanpa login
Route::get('/lapor', LaporanPublik::class)->name('lapor.publik');

// ==========================================
// AUTHENTICATED ROUTES (butuh login)
// ==========================================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');

    // Pencarian global
    Route::get('/cari', Cari::class)->name('cari');

    // Inventaris — static routes must come before wildcard {inventaris}
    Route::get('/inventaris', InventarisIndex::class)->name('inventaris.index');
    Route::get('/inventaris/create', InventarisCreate::class)->name('inventaris.create');

    // Bahan Habis Pakai CRUD under Inventaris module (before {inventaris} wildcard)
    Route::get('/inventaris/consumables', BahanHabisPakaiIndex::class)->name('inventaris.bahan.index');
    Route::get('/inventaris/consumables/create', BahanCreate::class)->name('inventaris.bahan.create');
    Route::get('/inventaris/consumables/{bahan}', InventarisBahanShow::class)->name('inventaris.bahan.show');
    Route::get('/inventaris/consumables/{bahan}/edit', BahanEdit::class)->name('inventaris.bahan.edit');

    // Inventaris wildcard routes
    Route::get('/inventaris/{inventaris}', InventarisShow::class)->name('inventaris.show');
    Route::get('/inventaris/{inventaris}/edit', InventarisEdit::class)->name('inventaris.edit');

    // Bahan Habis Pakai (standalone module /bahan)
    Route::get('/bahan', BahanIndex::class)->name('bahan.index');
    Route::get('/bahan/{bahan}', BahanShow::class)->name('bahan.show');

    // Peminjaman
    Route::get('/peminjaman', PeminjamanIndex::class)->name('peminjaman.index');
    Route::get('/peminjaman/create', PeminjamanCreate::class)->name('peminjaman.create');

    // Laporan (internal — butuh login)
    Route::get('/laporan', LaporanIndex::class)->name('laporan.index');
    Route::get('/laporan/create', LaporanCreate::class)->name('laporan.create');
    Route::get('/laporan/{laporan}', LaporanShow::class)->name('laporan.show');

    // Lokasi — hanya yang punya permission
    Route::middleware('can:manage_lokasi')->group(function () {
        Route::get('/lokasi', LokasiIndex::class)->name('lokasi.index');
        Route::get('/lokasi/create', LokasiCreate::class)->name('lokasi.create');
        Route::get('/lokasi/{lokasi}', LokasiShow::class)->name('lokasi.show');
        Route::get('/lokasi/{lokasi}/ruangan/{ruangan}', RuanganShow::class)->name('ruangan.show');
    });

    // Master Data — hanya administrator
    Route::middleware('role:administrator')->group(function () {
        Route::get('/master-data', MasterDataIndex::class)->name('master-data');
        Route::get('/master-data/kategori', KategoriIndex::class)->name('master.kategori');
        Route::get('/master-data/users', UserIndex::class)->name('master.users');
        Route::get('/master-data/roles', RoleIndex::class)->name('master.roles');
        Route::get('/master-data/audit-log', AuditLogIndex::class)->name('master.audit-log');
    });
});
