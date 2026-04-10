<?php

use App\Livewire\Auth\Login;
use App\Livewire\Cari;
use App\Livewire\Dashboard;
use App\Livewire\Lokasi\LokasiIndex;
use App\Livewire\Lokasi\LokasiShow;
use App\Livewire\Lokasi\LokasiCreate;
use App\Livewire\Inventaris\InventarisIndex;
use App\Livewire\Inventaris\InventarisCreate;
use App\Livewire\Inventaris\InventarisEdit;
use App\Livewire\Inventaris\InventarisShow;
use App\Livewire\Peminjaman\PeminjamanIndex;
use App\Livewire\Peminjaman\PeminjamanCreate;
use App\Livewire\BahanHabisPakai\BahanIndex;
use App\Livewire\Laporan\LaporanIndex;
use App\Livewire\Laporan\LaporanCreate;
use App\Livewire\Laporan\LaporanShow;
use App\Livewire\Laporan\LaporanPublik;
use App\Livewire\MasterData\MasterDataIndex;
use App\Livewire\MasterData\KategoriIndex;
use App\Livewire\MasterData\UserIndex;
use App\Livewire\MasterData\RoleIndex;
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

    // Inventaris
    Route::get('/inventaris', InventarisIndex::class)->name('inventaris.index');
    Route::get('/inventaris/create', InventarisCreate::class)->name('inventaris.create');
    Route::get('/inventaris/{inventaris}', InventarisShow::class)->name('inventaris.show');
    Route::get('/inventaris/{inventaris}/edit', InventarisEdit::class)->name('inventaris.edit');

    // Bahan Habis Pakai
    Route::get('/bahan', BahanIndex::class)->name('bahan.index');

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
    });

    // Master Data — hanya administrator
    Route::middleware('role:administrator')->group(function () {
        Route::get('/master-data', MasterDataIndex::class)->name('master-data');
        Route::get('/master-data/kategori', KategoriIndex::class)->name('master.kategori');
        Route::get('/master-data/users', UserIndex::class)->name('master.users');
        Route::get('/master-data/roles', RoleIndex::class)->name('master.roles');
    });
});
