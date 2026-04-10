<?php

namespace App\Livewire\Lokasi;

use App\Models\Lokasi;
use Livewire\Component;

class LokasiIndex extends Component
{
    public string $search = '';

    public function deleteLokasi(int $id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->ruangans()->delete();
        $lokasi->delete();
    }

    public function render()
    {
        $lokasis = Lokasi::withCount('ruangans')
            ->with('ruangans')
            ->when($this->search, fn($q) => $q->where('nama', 'like', "%{$this->search}%"))
            ->latest()
            ->get();

        $totalInfrastruktur = Lokasi::count();
        $gedungUtama = Lokasi::where('tipe', 'utama')->count();

        return view('livewire.lokasi.lokasi-index', compact('lokasis', 'totalInfrastruktur', 'gedungUtama'));
    }
}
