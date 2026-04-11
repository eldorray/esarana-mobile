<?php

namespace App\Livewire\Inventaris;

use App\Models\BahanHabisPakai;
use App\Models\Kategori;
use App\Models\Ruangan;
use Livewire\Component;

class BahanEdit extends Component
{
    public BahanHabisPakai $bahan;

    public string $nama = '';
    public string $kode = '';
    public ?int $kategori_id = null;
    public ?int $ruangan_id = null;
    public int $stok = 0;
    public string $satuan = 'pcs';
    public int $stok_minimum = 5;
    public float $harga_satuan = 0;

    public function mount(BahanHabisPakai $bahan): void
    {
        $this->bahan        = $bahan;
        $this->nama         = $bahan->nama;
        $this->kode         = $bahan->kode;
        $this->kategori_id  = $bahan->kategori_id;
        $this->ruangan_id   = $bahan->ruangan_id;
        $this->stok         = $bahan->stok;
        $this->satuan       = $bahan->satuan;
        $this->stok_minimum = $bahan->stok_minimum;
        $this->harga_satuan = (float) $bahan->harga_satuan;
    }

    public function save()
    {
        $this->validate([
            'nama'         => 'required|min:2',
            'kode'         => 'required|unique:bahan_habis_pakais,kode,' . $this->bahan->id,
            'kategori_id'  => 'nullable|exists:kategoris,id',
            'ruangan_id'   => 'nullable|exists:ruangans,id',
            'stok'         => 'required|integer|min:0',
            'satuan'       => 'required|string|max:30',
            'stok_minimum' => 'required|integer|min:0',
            'harga_satuan' => 'nullable|numeric|min:0',
        ]);

        try {
            $this->bahan->update([
                'nama'         => $this->nama,
                'kode'         => $this->kode,
                'kategori_id'  => $this->kategori_id,
                'ruangan_id'   => $this->ruangan_id,
                'stok'         => $this->stok,
                'satuan'       => $this->satuan,
                'stok_minimum' => $this->stok_minimum,
                'harga_satuan' => $this->harga_satuan,
            ]);

            session()->flash('success', 'Data berhasil diperbarui.');
            return $this->redirect(route('inventaris.bahan.show', $this->bahan), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan perubahan. Silakan coba lagi.');
        }
    }

    public function render()
    {
        $kategoris = Kategori::where('is_active', true)->get();
        $ruangans  = Ruangan::with('lokasi')->get();
        return view('livewire.inventaris.bahan-edit', compact('kategoris', 'ruangans'));
    }
}
