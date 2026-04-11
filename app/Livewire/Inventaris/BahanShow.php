<?php

namespace App\Livewire\Inventaris;

use App\Models\BahanHabisPakai;
use Livewire\Component;

class BahanShow extends Component
{
    public BahanHabisPakai $bahan;

    public function mount(BahanHabisPakai $bahan): void
    {
        $this->bahan = $bahan->load(['kategori', 'ruangan.lokasi']);
    }

    public function render()
    {
        return view('livewire.inventaris.bahan-show');
    }
}
