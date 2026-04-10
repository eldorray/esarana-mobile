<?php

namespace App\Livewire\Inventaris;

use App\Models\Inventaris;
use App\Models\Kategori;
use App\Models\Ruangan;
use Livewire\Component;
use Livewire\WithFileUploads;

class InventarisCreate extends Component
{
    use WithFileUploads;

    public int $step = 1;

    // Step 1
    public string $nama = '';
    public string $kode = '';
    public string $deskripsi = '';
    public ?int $ruangan_id = null;
    public $gambar = null;

    // Step 2
    public ?int $kategori_id = null;
    public string $serial_number = '';
    public string $kondisi = 'baik';
    public float $nilai_aset = 0;
    public string $tanggal_perolehan = '';

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'nama' => 'required|min:3',
                'kode' => 'required|unique:inventaris,kode',
                'gambar' => 'nullable|image|max:2048',
            ]);
        }
        $this->step = 2;
    }

    public function prevStep()
    {
        $this->step = 1;
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|min:3',
            'kode' => 'required|unique:inventaris,kode',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'ruangan_id' => 'nullable|exists:ruangans,id',
            'kondisi' => 'required|in:baik,cukup,rusak_ringan,rusak_berat',
            'nilai_aset' => 'nullable|numeric|min:0',
            'tanggal_perolehan' => 'nullable|date',
        ]);

        try {
            $data = [
                'nama' => $this->nama,
                'kode' => $this->kode,
                'deskripsi' => $this->deskripsi,
                'ruangan_id' => $this->ruangan_id,
                'kategori_id' => $this->kategori_id,
                'serial_number' => $this->serial_number,
                'kondisi' => $this->kondisi,
                'nilai_aset' => $this->nilai_aset,
                'tanggal_perolehan' => $this->tanggal_perolehan ?: null,
                'status' => 'tersedia',
            ];

            if ($this->gambar) {
                $data['gambar'] = $this->gambar->store('inventaris', 'public');
            }

            $inventaris = Inventaris::create($data);

            session()->flash('success', 'Inventaris "' . $this->nama . '" berhasil ditambahkan.');
            return $this->redirect(route('inventaris.show', $inventaris), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan data. Silakan coba lagi.');
        }
    }

    public function render()
    {
        $kategoris = Kategori::where('is_active', true)->get();
        $ruangans = Ruangan::with('lokasi')->get();
        return view('livewire.inventaris.inventaris-create', compact('kategoris', 'ruangans'));
    }
}
