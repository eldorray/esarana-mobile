<?php

namespace App\Livewire\MasterData;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleIndex extends Component
{
    public string $name = '';
    public array $selectedPermissions = [];
    public bool $showForm = false;
    public ?int $editId = null;

    public function openForm(?int $id = null)
    {
        if ($id) {
            $role = Role::with('permissions')->findOrFail($id);
            $this->editId = $id;
            $this->name = $role->name;
            $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:2',
        ]);

        if ($this->editId) {
            $role = Role::find($this->editId);
            $role->update(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions);
        } else {
            $role = Role::create(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions);
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id)
    {
        Role::findOrFail($id)->delete();
    }

    private function resetForm()
    {
        $this->editId = null;
        $this->name = '';
        $this->selectedPermissions = [];
    }

    public function render()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('livewire.master-data.role-index', compact('roles', 'permissions'));
    }
}
