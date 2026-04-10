<?php

namespace App\Livewire\Laporan;

use App\Models\Laporan;
use Livewire\Component;

class LaporanIndex extends Component
{
    public function updateStatus(int $id, string $status)
    {
        $user = auth()->user();

        // Only admin & supervisor can change status
        if (! $user->hasAnyRole(['administrator', 'supervisor'])) {
            return;
        }

        $laporan = Laporan::findOrFail($id);
        $laporan->update(['status' => $status]);
    }

    public function render()
    {
        $laporans = Laporan::with('user')->latest()->get();
        $canManage = auth()->user()->hasAnyRole(['administrator', 'supervisor']);

        return view('livewire.laporan.laporan-index', compact('laporans', 'canManage'));
    }
}
