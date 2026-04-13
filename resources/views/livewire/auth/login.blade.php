<div class="animate-fade-in"
     x-data="{
         installable: false,
         ios: /iphone|ipad|ipod/i.test(navigator.userAgent) && !window.navigator.standalone,
         install() { window.__pwaPrompt?.prompt(); window.__pwaPrompt = null; this.installable = false; }
     }"
     x-init="
         if (window.__pwaPrompt) installable = true;
         window.addEventListener('pwa-installable', () => installable = true);
     ">

    {{-- Android / Chrome: install banner --}}
    <div x-show="installable" x-cloak
         class="mb-6 card-elevated p-4 flex items-center gap-3 animate-slide-up">
        <div class="icon-container bg-primary-10 shrink-0">
            <span class="material-symbols-outlined text-primary text-xl">install_mobile</span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-on-surface">Pasang e-SARPRAS</p>
            <p class="text-[11px] text-on-surface-variant">Akses cepat dari layar utama</p>
        </div>
        <button @click="install()" class="btn-primary px-4 py-2 text-xs font-bold shrink-0">Pasang</button>
        <button @click="installable = false" class="text-on-surface-variant ml-1">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>

    {{-- iOS: manual guide --}}
    <div x-show="ios" x-cloak
         class="mb-6 card-elevated p-4 animate-slide-up">
        <div class="flex items-start gap-3">
            <div class="icon-container bg-primary-10 shrink-0">
                <span class="material-symbols-outlined text-primary text-xl">ios_share</span>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-on-surface mb-1">Tambah ke Layar Utama</p>
                <ol class="text-[11px] text-on-surface-variant space-y-1 list-decimal list-inside">
                    <li>Tap ikon <strong>Share</strong> <span class="inline-block">⬆</span> di bawah Safari</li>
                    <li>Gulir dan pilih <strong>"Add to Home Screen"</strong></li>
                    <li>Tap <strong>Add</strong> di pojok kanan atas</li>
                </ol>
            </div>
            <button @click="ios = false" class="text-on-surface-variant shrink-0">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
    </div>

    {{-- Brand --}}
    <div class="text-center mb-10">
        <div class="w-20 h-20 rounded-2xl overflow-hidden mx-auto mb-4 shadow-lg shadow-primary/20">
            <img src="/icons/icon-192.png" alt="e-SARPRAS" class="w-full h-full object-cover">
        </div>
        <h1 class="text-2xl font-extrabold font-headline text-on-surface tracking-tight">e-SARPRAS</h1>
        <p class="text-on-surface-variant text-sm mt-1">Masuk ke akun Anda</p>
    </div>

    {{-- Login Form --}}
    <form wire:submit="login" class="space-y-5">
        <div>
            <label for="email" class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Email</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">mail</span>
                <input wire:model="email" id="email" type="email" class="input-precision pl-11" placeholder="admin@esarana.id" autocomplete="email">
            </div>
            @error('email')
            <p class="text-error text-xs mt-1.5 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}
            </p>
            @enderror
        </div>

        <div>
            <label for="password" class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Password</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">lock</span>
                <input wire:model="password" id="password" type="password" class="input-precision pl-11" placeholder="••••••••" autocomplete="current-password">
            </div>
            @error('password')
            <p class="text-error text-xs mt-1.5 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}
            </p>
            @enderror
        </div>

        <label class="flex items-center gap-2.5 cursor-pointer">
            <input wire:model="remember" type="checkbox" class="rounded border-outline-variant text-primary w-4 h-4 focus:ring-primary">
            <span class="text-sm text-on-surface-variant font-medium">Ingat saya</span>
        </label>

        <button type="submit" class="btn-primary w-full py-3.5 text-sm flex items-center justify-center gap-2 mt-2" wire:loading.attr="disabled">
            <span wire:loading.remove><span class="material-symbols-outlined text-sm">login</span></span>
            <span wire:loading><span class="material-symbols-outlined text-sm animate-spin">progress_activity</span></span>
            <span wire:loading.remove>Masuk</span>
            <span wire:loading>Memproses...</span>
        </button>
    </form>

    <div class="flex items-center gap-3 my-8">
        <div class="flex-1 divider"></div>
        <span class="text-xs text-on-surface-variant font-medium">atau</span>
        <div class="flex-1 divider"></div>
    </div>

    <a href="{{ route('lapor.publik') }}" wire:navigate class="card-elevated p-4 flex items-center gap-3 group active:scale-[0.98] transition-transform block">
        <div class="icon-container bg-tertiary-10">
            <span class="material-symbols-outlined text-tertiary text-xl">campaign</span>
        </div>
        <div class="flex-1">
            <p class="text-sm font-bold text-on-surface">Lapor Masalah</p>
            <p class="text-[11px] text-on-surface-variant">Laporkan tanpa perlu login</p>
        </div>
        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary group-hover:translate-x-1 transition-all">arrow_forward</span>
    </a>

    <p class="text-center text-[11px] text-on-surface-variant mt-8">
        e-SARPRAS — Sistem Manajemen Sarana Prasarana Digital
    </p>
</div>
