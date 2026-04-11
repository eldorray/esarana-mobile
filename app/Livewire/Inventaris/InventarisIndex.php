<?php

namespace App\Livewire\Inventaris;

use App\Models\Inventaris;
use Livewire\Component;

class InventarisIndex extends Component
{
    public string $search = '';
    public string $tab = 'aset';
    public string $filter = '';

    public function render()
    {
        $inventaris = Inventaris::with(['kategori', 'ruangan.lokasi'])
            ->when($this->search, fn($q) => $q->where('nama', 'like', "%{$this->search}%"))
            ->when($this->filter, fn($q) => $q->where('status', $this->filter))
            ->latest()
            ->get();

        $totalAset = Inventaris::count();
        $perluMaintenance = Inventaris::where('status', 'maintenance')->count();

        return view('livewire.inventaris.inventaris-index', compact(
            'inventaris', 'totalAset', 'perluMaintenance'
        ));
    }
}
