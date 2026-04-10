<?php

namespace App\Livewire;

use App\Models\Inventaris;
use App\Models\BahanHabisPakai;
use App\Models\Laporan;
use App\Models\Peminjaman;
use Livewire\Component;

class Cari extends Component
{
    public string $query = '';

    public function render()
    {
        $inventaris = collect();
        $bahanHabisPakai = collect();
        $laporans = collect();

        if (strlen($this->query) >= 2) {
            $q = $this->query;

            $inventaris = Inventaris::with(['kategori', 'ruangan.lokasi'])
                ->where(function ($query) use ($q) {
                    $query->where('nama', 'like', "%{$q}%")
                          ->orWhere('kode', 'like', "%{$q}%")
                          ->orWhere('serial_number', 'like', "%{$q}%");
                })
                ->latest()
                ->take(5)
                ->get();

            $bahanHabisPakai = BahanHabisPakai::with(['kategori'])
                ->where(function ($query) use ($q) {
                    $query->where('nama', 'like', "%{$q}%")
                          ->orWhere('kode', 'like', "%{$q}%");
                })
                ->latest()
                ->take(5)
                ->get();

            $laporans = Laporan::where(function ($query) use ($q) {
                    $query->where('aset_lokasi', 'like', "%{$q}%")
                          ->orWhere('deskripsi', 'like', "%{$q}%");
                })
                ->latest()
                ->take(3)
                ->get();
        }

        $totalHasil = $inventaris->count() + $bahanHabisPakai->count() + $laporans->count();

        return view('livewire.cari', compact('inventaris', 'bahanHabisPakai', 'laporans', 'totalHasil'));
    }
}
