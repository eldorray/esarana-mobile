<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return <<<'HTML'
        <button wire:click="logout" class="w-full flex items-center gap-3 p-4 text-error hover:bg-error-container rounded-xl transition-colors active:scale-[0.98]">
            <div class="icon-container bg-danger-light">
                <span class="material-symbols-outlined text-error text-xl">logout</span>
            </div>
            <div class="text-left">
                <p class="text-sm font-bold">Keluar</p>
                <p class="text-[11px] text-on-surface-variant">Logout dari akun Anda</p>
            </div>
        </button>
        HTML;
    }
}
