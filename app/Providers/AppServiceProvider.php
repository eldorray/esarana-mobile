<?php

namespace App\Providers;

use App\Models\BahanHabisPakai;
use App\Models\Inventaris;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Peminjaman;
use App\Models\Ruangan;
use App\Models\User;
use App\Observers\AuditObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        foreach ([
            Inventaris::class,
            BahanHabisPakai::class,
            Kategori::class,
            Lokasi::class,
            Ruangan::class,
            Peminjaman::class,
            User::class,
        ] as $model) {
            $model::observe(AuditObserver::class);
        }
    }
}
