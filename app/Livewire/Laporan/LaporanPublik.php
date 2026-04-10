<?php

namespace App\Livewire\Laporan;

use App\Models\Laporan;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Lapor Masalah — eSarana')]
class LaporanPublik extends Component
{
    public string $nama_pelapor = '';
    public string $kontak_pelapor = '';
    public string $aset_lokasi = '';
    public string $tipe = 'kerusakan';
    public string $deskripsi = '';
    public bool $submitted = false;

    public function save()
    {
        $this->validate([
            'nama_pelapor' => 'required|min:2',
            'kontak_pelapor' => 'required|min:5',
            'aset_lokasi' => 'required|min:3',
            'tipe' => 'required|in:kerusakan,permintaan',
            'deskripsi' => 'required|min:10',
        ]);

        Laporan::create([
            'user_id' => null,
            'nama_pelapor' => $this->nama_pelapor,
            'kontak_pelapor' => $this->kontak_pelapor,
            'aset_lokasi' => $this->aset_lokasi,
            'tipe' => $this->tipe,
            'kategori_laporan' => $this->tipe,
            'deskripsi' => $this->deskripsi,
            'status' => 'baru',
        ]);

        $this->submitted = true;
    }

    public function resetForm()
    {
        $this->reset(['nama_pelapor', 'kontak_pelapor', 'aset_lokasi', 'tipe', 'deskripsi', 'submitted']);
    }

    public function render()
    {
        return view('livewire.laporan.laporan-publik');
    }
}
