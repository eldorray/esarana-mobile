<?php

namespace App\Livewire\Laporan;

use App\Models\Laporan;
use Livewire\Component;

class LaporanCreate extends Component
{
    public string $aset_lokasi = '';
    public string $tipe = 'kerusakan';
    public string $kategori_laporan = 'kerusakan';
    public string $deskripsi = '';

    public function save()
    {
        $this->validate([
            'aset_lokasi' => 'required|min:3',
            'tipe' => 'required|in:kerusakan,permintaan',
            'deskripsi' => 'required|min:10',
        ]);

        Laporan::create([
            'user_id' => auth()->id(),
            'aset_lokasi' => $this->aset_lokasi,
            'tipe' => $this->tipe,
            'kategori_laporan' => $this->tipe,
            'deskripsi' => $this->deskripsi,
        ]);

        return $this->redirect(route('laporan.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.laporan.laporan-create');
    }
}
