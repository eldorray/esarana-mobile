<?php

namespace App\Livewire;

use App\Models\BahanHabisPakai;
use App\Models\Laporan;
use App\Models\Peminjaman;
use Livewire\Component;

class Notifikasi extends Component
{
    public function render()
    {
        $user     = auth()->user();
        $isStaff  = $user->hasRole('staff');
        $items    = collect();

        // 1. Peminjaman terlambat
        $overdueQuery = Peminjaman::with(['inventaris', 'user'])
            ->where('status', 'aktif')
            ->whereDate('tanggal_kembali_rencana', '<', today());

        if ($isStaff) {
            $overdueQuery->where('user_id', $user->id);
        }

        foreach ($overdueQuery->latest('tanggal_kembali_rencana')->take(5)->get() as $p) {
            $days = today()->diffInDays($p->tanggal_kembali_rencana);
            $items->push([
                'type'    => 'overdue',
                'icon'    => 'schedule',
                'color'   => 'error',
                'bg'      => 'bg-danger-light',
                'title'   => $p->inventaris?->nama ?? 'Aset',
                'body'    => 'Terlambat ' . $days . ' hari • ' . ($p->user?->name ?? '—'),
                'link'    => route('peminjaman.index'),
                'time'    => $p->tanggal_kembali_rencana->diffForHumans(),
            ]);
        }

        // 2. Laporan baru (admin/supervisor lihat semua; staff lihat milik sendiri)
        $laporanQuery = Laporan::where('status', 'baru');
        if ($isStaff) {
            $laporanQuery->where('user_id', $user->id);
        }

        foreach ($laporanQuery->latest()->take(5)->get() as $l) {
            $items->push([
                'type'    => 'laporan',
                'icon'    => 'report',
                'color'   => 'tertiary',
                'bg'      => 'bg-tertiary-10',
                'title'   => $l->aset_lokasi,
                'body'    => ucfirst($l->tipe) . ' • ' . $l->pelapor_name,
                'link'    => route('laporan.show', $l),
                'time'    => $l->created_at->diffForHumans(),
            ]);
        }

        // 3. Stok kritis (semua role)
        foreach (BahanHabisPakai::whereColumn('stok', '<=', 'stok_minimum')->take(5)->get() as $b) {
            $items->push([
                'type'    => 'stok',
                'icon'    => 'warning',
                'color'   => 'tertiary',
                'bg'      => 'bg-tertiary-10',
                'title'   => $b->nama,
                'body'    => 'Stok ' . $b->stok . ' ' . $b->satuan . ' (min ' . $b->stok_minimum . ')',
                'link'    => route('inventaris.bahan.show', $b),
                'time'    => 'Kritis',
            ]);
        }

        // Sort: overdue first, then laporan, then stok
        $sorted = $items->sortBy(fn($i) => match($i['type']) {
            'overdue' => 0,
            'laporan' => 1,
            default   => 2,
        })->values();

        return view('livewire.notifikasi', ['items' => $sorted]);
    }
}
