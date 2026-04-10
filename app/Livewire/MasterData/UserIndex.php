<?php

namespace App\Livewire\MasterData;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserIndex extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = '';
    public bool $showForm = false;
    public ?int $editId = null;

    public function openForm(?int $id = null)
    {
        if ($id) {
            $user = User::findOrFail($id);
            $this->editId = $id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->roles->first()?->name ?? '';
            $this->password = '';
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'role' => 'required',
        ];

        if (!$this->editId) {
            $rules['password'] = 'required|min:8';
            $rules['email'] .= '|unique:users,email';
        }

        $this->validate($rules);

        if ($this->editId) {
            $user = User::find($this->editId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
            if ($this->password) {
                $user->update(['password' => Hash::make($this->password)]);
            }
            $user->syncRoles([$this->role]);
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $user->assignRole($this->role);
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id)
    {
        User::findOrFail($id)->delete();
    }

    private function resetForm()
    {
        $this->editId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = '';
    }

    public function render()
    {
        $users = User::with('roles')->latest()->get();
        $roles = Role::all();
        return view('livewire.master-data.user-index', compact('users', 'roles'));
    }
}
