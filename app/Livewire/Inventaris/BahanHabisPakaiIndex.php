<?php

namespace App\Livewire\Inventaris;

use App\Models\BahanHabisPakai;
use Livewire\Component;

class BahanHabisPakaiIndex extends Component
{
    public string $search = '';

    public function hapus(int $id): void
    {
        try {
            $bahan = BahanHabisPakai::findOrFail($id);
            $nama  = $bahan->nama;
            $bahan->delete();
            session()->flash('success', '"' . $nama . '" berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus item. Silakan coba lagi.');
        }
    }

    public function render()
    {
        $bahans = BahanHabisPakai::with(['kategori', 'ruangan.lokasi'])
            ->when($this->search, fn($q) => $q->where('nama', 'like', "%{$this->search}%")
                ->orWhere('kode', 'like', "%{$this->search}%"))
            ->latest()
            ->get();

        $stokKritis = BahanHabisPakai::whereColumn('stok', '<=', 'stok_minimum')->count();

        return view('livewire.inventaris.bahan-habis-pakai-index', compact('bahans', 'stokKritis'));
    }
}
