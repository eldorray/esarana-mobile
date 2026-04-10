<div class="animate-fade-in">
    {{-- Brand --}}
    <div class="text-center mb-10">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center mx-auto mb-5 shadow-lg shadow-primary/20">
            <span class="text-white font-extrabold text-2xl font-headline">eS</span>
        </div>
        <h1 class="text-2xl font-extrabold font-headline text-on-surface tracking-tight">Selamat Datang</h1>
        <p class="text-on-surface-variant text-sm mt-1">Masuk ke akun eSarana Anda</p>
    </div>

    {{-- Login Form --}}
    <form wire:submit="login" class="space-y-5">
        {{-- Email --}}
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

        {{-- Password --}}
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

        {{-- Remember Me --}}
        <label class="flex items-center gap-2.5 cursor-pointer">
            <input wire:model="remember" type="checkbox" class="rounded border-outline-variant text-primary w-4 h-4 focus:ring-primary">
            <span class="text-sm text-on-surface-variant font-medium">Ingat saya</span>
        </label>

        {{-- Submit --}}
        <button type="submit" class="btn-primary w-full py-3.5 text-sm flex items-center justify-center gap-2 mt-2" wire:loading.attr="disabled">
            <span wire:loading.remove>
                <span class="material-symbols-outlined text-sm">login</span>
            </span>
            <span wire:loading>
                <span class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
            </span>
            <span wire:loading.remove>Masuk</span>
            <span wire:loading>Memproses...</span>
        </button>
    </form>

    {{-- Divider --}}
    <div class="flex items-center gap-3 my-8">
        <div class="flex-1 divider"></div>
        <span class="text-xs text-on-surface-variant font-medium">atau</span>
        <div class="flex-1 divider"></div>
    </div>

    {{-- Public Report Link --}}
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

    {{-- Footer --}}
    <p class="text-center text-[11px] text-on-surface-variant mt-8">
        eSarana v1.0 — Precision Architect
    </p>
</div>
