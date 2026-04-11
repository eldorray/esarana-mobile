<?php

namespace App\Livewire\Lokasi;

use App\Models\Lokasi;
use App\Models\Ruangan;
use Livewire\Component;

class LokasiShow extends Component
{
    public Lokasi $lokasi;

    // Edit Lokasi
    public bool $editingLokasi = false;
    public string $nama = '';
    public string $alamat = '';
    public string $tipe = 'utama';
    public string $status = 'operasional';

    // Ruangan Form
    public bool $showRuanganForm = false;
    public ?int $editRuanganId = null;
    public string $ruanganNama = '';
    public string $ruanganLantai = '';
    public ?int $ruanganKapasitas = null;
    public string $ruanganStatus = 'aktif';

    public function mount(Lokasi $lokasi)
    {
        $this->lokasi = $lokasi->load([
            'ruangans',
            'ruangans.inventaris',
            'ruangans.bahanHabisPakais',
        ]);
        $this->nama = $lokasi->nama;
        $this->alamat = $lokasi->alamat ?? '';
        $this->tipe = $lokasi->tipe;
        $this->status = $lokasi->status;
    }

    // === Lokasi Edit ===
    public function toggleEditLokasi()
    {
        $this->editingLokasi = !$this->editingLokasi;
    }

    public function updateLokasi()
    {
        $this->validate([
            'nama' => 'required|min:3',
            'tipe' => 'required|in:utama,satelit,gudang',
            'status' => 'required|in:operasional,renovasi,nonaktif',
        ]);

        $this->lokasi->update([
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'tipe' => $this->tipe,
            'status' => $this->status,
        ]);

        $this->editingLokasi = false;
        $this->lokasi->refresh();
    }

    public function deleteLokasi()
    {
        $this->lokasi->ruangans()->delete();
        $this->lokasi->delete();

        return $this->redirect(route('lokasi.index'), navigate: true);
    }

    // === Ruangan CRUD ===
    public function openRuanganForm(?int $id = null)
    {
        if ($id) {
            $ruangan = Ruangan::findOrFail($id);
            $this->editRuanganId = $ruangan->id;
            $this->ruanganNama = $ruangan->nama;
            $this->ruanganLantai = $ruangan->lantai ?? '';
            $this->ruanganKapasitas = $ruangan->kapasitas;
            $this->ruanganStatus = $ruangan->status;
        } else {
            $this->resetRuanganForm();
        }
        $this->showRuanganForm = true;
    }

    public function saveRuangan()
    {
        $this->validate([
            'ruanganNama' => 'required|min:2',
            'ruanganLantai' => 'nullable',
            'ruanganKapasitas' => 'nullable|integer|min:1',
            'ruanganStatus' => 'required|in:aktif,nonaktif',
        ]);

        $data = [
            'lokasi_id' => $this->lokasi->id,
            'nama' => $this->ruanganNama,
            'lantai' => $this->ruanganLantai,
            'kapasitas' => $this->ruanganKapasitas,
            'status' => $this->ruanganStatus,
        ];

        if ($this->editRuanganId) {
            Ruangan::findOrFail($this->editRuanganId)->update($data);
        } else {
            Ruangan::create($data);
        }

        $this->showRuanganForm = false;
        $this->resetRuanganForm();
        $this->lokasi->load(['ruangans', 'ruangans.inventaris', 'ruangans.bahanHabisPakais']);
    }

    public function deleteRuangan(int $id)
    {
        Ruangan::findOrFail($id)->delete();
        $this->lokasi->load(['ruangans', 'ruangans.inventaris', 'ruangans.bahanHabisPakais']);
    }

    private function resetRuanganForm()
    {
        $this->editRuanganId = null;
        $this->ruanganNama = '';
        $this->ruanganLantai = '';
        $this->ruanganKapasitas = null;
        $this->ruanganStatus = 'aktif';
    }

    public function render()
    {
        return view('livewire.lokasi.lokasi-show');
    }
}
