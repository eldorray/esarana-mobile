<?php

namespace App\Livewire\Laporan;

use App\Models\Laporan;
use Livewire\Component;

class LaporanShow extends Component
{
    public Laporan $laporan;

    public function mount(Laporan $laporan): void
    {
        $this->laporan = $laporan->load('user');
    }

    public function updateStatus(string $status): void
    {
        $user = auth()->user();

        if (! $user->hasAnyRole(['administrator', 'supervisor'])) {
            return;
        }

        try {
            $this->laporan->update(['status' => $status]);
            $this->laporan->refresh();
            session()->flash('success', 'Status laporan berhasil diperbarui.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui status laporan.');
        }
    }

    public function render()
    {
        $canManage = auth()->user()->hasAnyRole(['administrator', 'supervisor']);
        return view('livewire.laporan.laporan-show', compact('canManage'));
    }
}
