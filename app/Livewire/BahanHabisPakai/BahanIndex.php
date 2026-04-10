<?php

namespace App\Livewire\BahanHabisPakai;

use App\Models\BahanHabisPakai;
use Livewire\Component;

class BahanIndex extends Component
{
    public string $search = '';

    public function render()
    {
        $bahans = BahanHabisPakai::with(['kategori', 'ruangan'])
            ->when($this->search, fn($q) => $q->where('nama', 'like', "%{$this->search}%"))
            ->latest()
            ->get();

        $stokKritis = BahanHabisPakai::whereColumn('stok', '<=', 'stok_minimum')->count();

        return view('livewire.bahan-habis-pakai.bahan-index', compact('bahans', 'stokKritis'));
    }
}
