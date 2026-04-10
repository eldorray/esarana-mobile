<?php

namespace App\Livewire\Peminjaman;

use App\Models\Inventaris;
use App\Models\Peminjaman;
use Livewire\Component;

class PeminjamanCreate extends Component
{
    public ?int $inventaris_id = null;
    public string $tanggal_pinjam = '';
    public string $tanggal_kembali_rencana = '';
    public string $catatan = '';

    public function mount()
    {
        $this->tanggal_pinjam = now()->format('Y-m-d');
        $this->tanggal_kembali_rencana = now()->addWeek()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            $inventaris = Inventaris::findOrFail($this->inventaris_id);

            if ($inventaris->status !== 'tersedia') {
                $this->addError('inventaris_id', 'Aset ini tidak tersedia untuk dipinjam saat ini.');
                return;
            }

            Peminjaman::create([
                'user_id' => auth()->id(),
                'inventaris_id' => $this->inventaris_id,
                'tanggal_pinjam' => $this->tanggal_pinjam,
                'tanggal_kembali_rencana' => $this->tanggal_kembali_rencana,
                'catatan' => $this->catatan,
                'status' => 'aktif',
            ]);

            $inventaris->update(['status' => 'dipinjam']);

            session()->flash('success', 'Peminjaman "' . $inventaris->nama . '" berhasil diajukan.');
            return $this->redirect(route('peminjaman.index'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengajukan peminjaman. Silakan coba lagi.');
        }
    }

    public function render()
    {
        $inventarisTersedia = Inventaris::where('status', 'tersedia')->get();
        return view('livewire.peminjaman.peminjaman-create', compact('inventarisTersedia'));
    }
}
