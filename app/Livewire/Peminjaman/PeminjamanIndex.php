<?php

namespace App\Livewire\Peminjaman;

use App\Models\Peminjaman;
use Livewire\Component;

class PeminjamanIndex extends Component
{
    public function kembalikan(int $id): void
    {
        try {
            $peminjaman = Peminjaman::with('inventaris')->findOrFail($id);

            if ($peminjaman->status !== 'aktif') {
                session()->flash('error', 'Peminjaman ini sudah tidak aktif.');
                return;
            }

            $peminjaman->update([
                'status' => 'selesai',
                'tanggal_kembali_aktual' => now()->toDateString(),
            ]);

            $peminjaman->inventaris?->update(['status' => 'tersedia']);

            session()->flash('success', 'Aset "' . ($peminjaman->inventaris?->nama ?? 'Item') . '" berhasil dikembalikan.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses pengembalian. Silakan coba lagi.');
        }
    }

    public function render()
    {
        $peminjamanAktif = Peminjaman::with(['user', 'inventaris'])
            ->where('status', 'aktif')
            ->latest()
            ->get();

        $peminjamanSelesai = Peminjaman::with(['user', 'inventaris'])
            ->where('status', 'selesai')
            ->latest()
            ->take(5)
            ->get();

        $totalPinjam = Peminjaman::whereMonth('created_at', now()->month)->count();
        $terlambat = Peminjaman::where('status', 'aktif')
            ->whereDate('tanggal_kembali_rencana', '<', now())
            ->count();

        $persenTerpakai = Peminjaman::where('status', 'aktif')->count();

        return view('livewire.peminjaman.peminjaman-index', compact(
            'peminjamanAktif', 'peminjamanSelesai', 'totalPinjam', 'terlambat', 'persenTerpakai'
        ));
    }
}
