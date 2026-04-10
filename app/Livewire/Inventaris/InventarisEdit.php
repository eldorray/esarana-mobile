<?php

namespace App\Livewire\Inventaris;

use App\Models\Inventaris;
use App\Models\Kategori;
use App\Models\Ruangan;
use Livewire\Component;
use Livewire\WithFileUploads;

class InventarisEdit extends Component
{
    use WithFileUploads;

    public Inventaris $inventaris;

    public string $nama = '';
    public string $kode = '';
    public string $deskripsi = '';
    public ?int $ruangan_id = null;
    public ?int $kategori_id = null;
    public string $serial_number = '';
    public string $kondisi = 'baik';
    public string $status = 'tersedia';
    public float $nilai_aset = 0;
    public string $tanggal_perolehan = '';
    public $gambar = null;

    public function mount(Inventaris $inventaris): void
    {
        $this->inventaris = $inventaris;
        $this->nama = $inventaris->nama;
        $this->kode = $inventaris->kode;
        $this->deskripsi = $inventaris->deskripsi ?? '';
        $this->ruangan_id = $inventaris->ruangan_id;
        $this->kategori_id = $inventaris->kategori_id;
        $this->serial_number = $inventaris->serial_number ?? '';
        $this->kondisi = $inventaris->kondisi ?? 'baik';
        $this->status = $inventaris->status ?? 'tersedia';
        $this->nilai_aset = (float) ($inventaris->nilai_aset ?? 0);
        $this->tanggal_perolehan = $inventaris->tanggal_perolehan
            ? $inventaris->tanggal_perolehan->format('Y-m-d')
            : '';
    }

    protected function rules(): array
    {
        return [
            'nama' => 'required|min:3',
            'kode' => 'required|unique:inventaris,kode,' . $this->inventaris->id,
            'deskripsi' => 'nullable|string',
            'ruangan_id' => 'nullable|exists:ruangans,id',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'serial_number' => 'nullable|string|max:100',
            'kondisi' => 'required|in:baik,cukup,rusak_ringan,rusak_berat',
            'status' => 'required|in:tersedia,dipinjam,maintenance,rusak',
            'nilai_aset' => 'nullable|numeric|min:0',
            'tanggal_perolehan' => 'nullable|date',
            'gambar' => 'nullable|image|max:2048',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'nama' => $this->nama,
                'kode' => $this->kode,
                'deskripsi' => $this->deskripsi,
                'ruangan_id' => $this->ruangan_id,
                'kategori_id' => $this->kategori_id,
                'serial_number' => $this->serial_number,
                'kondisi' => $this->kondisi,
                'status' => $this->status,
                'nilai_aset' => $this->nilai_aset,
                'tanggal_perolehan' => $this->tanggal_perolehan ?: null,
            ];

            if ($this->gambar) {
                // Hapus gambar lama jika ada
                if ($this->inventaris->gambar && \Storage::disk('public')->exists($this->inventaris->gambar)) {
                    \Storage::disk('public')->delete($this->inventaris->gambar);
                }
                $data['gambar'] = $this->gambar->store('inventaris', 'public');
            }

            $this->inventaris->update($data);

            session()->flash('success', 'Data inventaris "' . $this->nama . '" berhasil diperbarui.');
            return $this->redirect(route('inventaris.show', $this->inventaris), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan perubahan. Silakan coba lagi.');
        }
    }

    public function render()
    {
        $kategoris = Kategori::where('is_active', true)->get();
        $ruangans = Ruangan::with('lokasi')->get();
        return view('livewire.inventaris.inventaris-edit', compact('kategoris', 'ruangans'));
    }
}
