<?php

namespace App\Livewire\BahanHabisPakai;

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
        return view('livewire.bahan-habis-pakai.bahan-show');
    }
}
