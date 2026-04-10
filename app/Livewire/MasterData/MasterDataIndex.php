<?php

namespace App\Livewire\MasterData;

use Livewire\Component;
use App\Models\Kategori;
use App\Models\User;
use Spatie\Permission\Models\Role;

class MasterDataIndex extends Component
{
    public function render()
    {
        $totalKategori = Kategori::count();
        $kategoriAktif = Kategori::where('is_active', true)->count();
        $totalUser = User::count();
        $roles = Role::all();

        return view('livewire.master-data.master-data-index', compact(
            'totalKategori', 'kategoriAktif', 'totalUser', 'roles'
        ));
    }
}
