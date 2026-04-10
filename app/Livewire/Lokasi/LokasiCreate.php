<?php

namespace App\Livewire\Lokasi;

use App\Models\Lokasi;
use Livewire\Component;

class LokasiCreate extends Component
{
    public string $nama = '';
    public string $alamat = '';
    public string $tipe = 'utama';
    public string $status = 'operasional';

    public function save()
    {
        $this->validate([
            'nama' => 'required|min:3',
            'alamat' => 'nullable',
            'tipe' => 'required|in:utama,satelit,gudang',
            'status' => 'required|in:operasional,renovasi,nonaktif',
        ]);

        Lokasi::create([
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'tipe' => $this->tipe,
            'status' => $this->status,
        ]);

        return $this->redirect(route('lokasi.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.lokasi.lokasi-create');
    }
}
