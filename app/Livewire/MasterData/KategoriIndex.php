<?php

namespace App\Livewire\MasterData;

use App\Models\Kategori;
use Livewire\Component;

class KategoriIndex extends Component
{
    public string $nama = '';
    public string $deskripsi = '';
    public string $icon = 'category';
    public bool $showForm = false;
    public ?int $editId = null;

    public function openForm(?int $id = null)
    {
        if ($id) {
            $kat = Kategori::findOrFail($id);
            $this->editId = $id;
            $this->nama = $kat->nama;
            $this->deskripsi = $kat->deskripsi ?? '';
            $this->icon = $kat->icon ?? 'category';
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|min:2',
        ]);

        if ($this->editId) {
            Kategori::find($this->editId)->update([
                'nama' => $this->nama,
                'deskripsi' => $this->deskripsi,
                'icon' => $this->icon,
            ]);
        } else {
            Kategori::create([
                'nama' => $this->nama,
                'deskripsi' => $this->deskripsi,
                'icon' => $this->icon,
            ]);
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id)
    {
        Kategori::findOrFail($id)->delete();
    }

    public function toggleActive(int $id)
    {
        $kat = Kategori::findOrFail($id);
        $kat->update(['is_active' => !$kat->is_active]);
    }

    private function resetForm()
    {
        $this->editId = null;
        $this->nama = '';
        $this->deskripsi = '';
        $this->icon = 'category';
    }

    public function render()
    {
        $kategoris = Kategori::withCount('inventaris')->latest()->get();
        return view('livewire.master-data.kategori-index', compact('kategoris'));
    }
}
