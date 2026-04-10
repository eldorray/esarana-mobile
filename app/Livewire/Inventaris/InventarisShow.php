<?php

namespace App\Livewire\Inventaris;

use App\Models\Inventaris;
use Livewire\Component;

class InventarisShow extends Component
{
    public Inventaris $inventaris;

    public function mount(Inventaris $inventaris): void
    {
        $this->inventaris = $inventaris->load([
            'kategori',
            'ruangan.lokasi',
            'peminjamans.user',
        ]);
    }

    public function render()
    {
        return view('livewire.inventaris.inventaris-show');
    }
}
