<?php

namespace App\Livewire\Laporan;

use App\Models\Laporan;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.guest')]
#[Title('Lapor Masalah — e-SARPRAS')]
class LaporanPublik extends Component
{
    use WithFileUploads;

    public string $nama_pelapor = '';
    public string $kontak_pelapor = '';
    public string $aset_lokasi = '';
    public string $tipe = 'kerusakan';
    public string $deskripsi = '';
    public $foto = null;
    public bool $submitted = false;

    public function save()
    {
        $this->validate([
            'nama_pelapor' => 'required|min:2',
            'kontak_pelapor' => 'required|min:5',
            'aset_lokasi' => 'required|min:3',
            'tipe' => 'required|in:kerusakan,permintaan',
            'deskripsi' => 'required|min:10',
            'foto' => 'nullable|image|max:5120',
        ]);

        $fotoPath = $this->foto ? $this->foto->store('laporan', 'public') : null;

        Laporan::create([
            'user_id' => null,
            'nama_pelapor' => $this->nama_pelapor,
            'kontak_pelapor' => $this->kontak_pelapor,
            'aset_lokasi' => $this->aset_lokasi,
            'tipe' => $this->tipe,
            'kategori_laporan' => $this->tipe,
            'deskripsi' => $this->deskripsi,
            'foto' => $fotoPath,
            'status' => 'baru',
        ]);

        $this->submitted = true;
    }

    public function resetForm()
    {
        $this->reset(['nama_pelapor', 'kontak_pelapor', 'aset_lokasi', 'tipe', 'deskripsi', 'foto', 'submitted']);
    }

    public function render()
    {
        return view('livewire.laporan.laporan-publik');
    }
}
