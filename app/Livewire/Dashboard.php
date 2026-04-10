<?php

namespace App\Livewire;

use App\Models\BahanHabisPakai;
use App\Models\Inventaris;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Peminjaman;
use App\Models\Laporan;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $roleName = $user->roles->first()?->name ?? 'staff';
        $isAdmin = $roleName === 'administrator';
        $isSupervisor = $roleName === 'supervisor';
        $isStaff = $roleName === 'staff';

        $totalAset = Inventaris::count();
        $stokRendah = BahanHabisPakai::whereColumn('stok', '<=', 'stok_minimum')->count();
        $nilaiTotal = Inventaris::sum('nilai_aset');

        // Staff only sees their own data
        if ($isStaff) {
            $peminjamanAktif = Peminjaman::where('status', 'aktif')
                ->where('user_id', $user->id)->count();
            $permintaanBaru = Laporan::where('status', 'baru')
                ->where('user_id', $user->id)->count();
            $aktivitasTerbaru = Peminjaman::with(['user', 'inventaris'])
                ->where('user_id', $user->id)
                ->latest()->take(5)->get();
            $laporanTerbaru = Laporan::with('user')
                ->where('user_id', $user->id)
                ->latest()->take(3)->get();
        } else {
            $peminjamanAktif = Peminjaman::where('status', 'aktif')->count();
            $permintaanBaru = Laporan::where('status', 'baru')->count();
            $aktivitasTerbaru = Peminjaman::with(['user', 'inventaris'])
                ->latest()->take(5)->get();
            $laporanTerbaru = Laporan::with('user')
                ->latest()->take(3)->get();
        }

        $kategoriPopuler = Kategori::withCount('inventaris')
            ->orderByDesc('inventaris_count')
            ->take(3)
            ->get();

        $lokasis = Lokasi::withCount('ruangans')->take(3)->get();

        return view('livewire.dashboard', compact(
            'totalAset',
            'stokRendah',
            'peminjamanAktif',
            'permintaanBaru',
            'nilaiTotal',
            'kategoriPopuler',
            'aktivitasTerbaru',
            'lokasis',
            'laporanTerbaru',
            'roleName',
            'isAdmin',
            'isSupervisor',
            'isStaff',
        ));
    }
}
