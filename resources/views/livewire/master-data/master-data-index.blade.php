<div class="animate-fade-in">
    <section class="pt-3 mb-6">
        <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-widest">Pengaturan</p>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline mt-1">Master Data</h1>
        <p class="text-on-surface-variant text-sm mt-0.5">Kelola konfigurasi inti dan klasifikasi aset.</p>
    </section>

    {{-- Kategori Aset Card --}}
    <section class="mb-4 stagger-children">
        <a href="{{ route('master.kategori') }}" wire:navigate class="block card-elevated p-5 group active:scale-[0.98] transition-transform">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-container-lg bg-primary-10">
                    <span class="material-symbols-outlined text-primary text-2xl">category</span>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary group-hover:translate-x-1 transition-all duration-300">arrow_forward</span>
            </div>
            <h3 class="text-base font-bold text-on-surface font-headline">Kategori Aset</h3>
            <p class="text-sm text-on-surface-variant mt-1">Kelola klasifikasi hierarkis untuk semua inventaris.</p>
            <div class="flex gap-3 mt-4">
                <div class="bg-surface-container-low px-3.5 py-2 rounded-xl">
                    <span class="text-[10px] font-bold text-on-surface-variant uppercase block">Total</span>
                    <span class="text-lg font-extrabold text-on-surface font-headline">{{ $totalKategori }}</span>
                </div>
                <div class="bg-surface-container-low px-3.5 py-2 rounded-xl">
                    <span class="text-[10px] font-bold text-on-surface-variant uppercase block">Aktif</span>
                    <span class="text-lg font-extrabold text-primary font-headline">{{ $kategoriAktif }}</span>
                </div>
            </div>
        </a>
    </section>

    {{-- Manajemen User Card --}}
    <section class="mb-4">
        <a href="{{ route('master.users') }}" wire:navigate class="block card-elevated p-5 group active:scale-[0.98] transition-transform">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-container-lg bg-secondary-10">
                    <span class="material-symbols-outlined text-secondary text-2xl">manage_accounts</span>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary group-hover:translate-x-1 transition-all duration-300">arrow_forward</span>
            </div>
            <h3 class="text-base font-bold text-on-surface font-headline">Manajemen User</h3>
            <p class="text-sm text-on-surface-variant mt-1">Kontrol akses pengguna dan aktivitas login.</p>
            <div class="mt-3 flex items-center gap-2 text-on-surface-variant">
                <span class="material-symbols-outlined text-[16px]">group</span>
                <span class="text-sm font-medium">{{ $totalUser }} Pengguna terdaftar</span>
            </div>
        </a>
    </section>

    {{-- Manajemen Role Card --}}
    <section class="mb-4">
        <a href="{{ route('master.roles') }}" wire:navigate class="block card-elevated p-5 group active:scale-[0.98] transition-transform">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-container-lg bg-tertiary-10">
                    <span class="material-symbols-outlined text-tertiary text-2xl">verified_user</span>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary group-hover:translate-x-1 transition-all duration-300">arrow_forward</span>
            </div>
            <h3 class="text-base font-bold text-on-surface font-headline">Manajemen Role</h3>
            <p class="text-sm text-on-surface-variant mt-1">Definisikan izin akses untuk setiap level jabatan.</p>
            <div class="flex flex-wrap gap-1.5 mt-4">
                @foreach($roles as $role)
                @php
                    $roleColors = ['bg-primary', 'bg-secondary', 'bg-tertiary'];
                    $colorClass = $roleColors[$loop->index % 3] ?? 'bg-primary';
                @endphp
                <span class="{{ $colorClass }} text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                    {{ $role->name }}
                </span>
                @endforeach
            </div>
        </a>
    </section>

    {{-- Audit Log --}}
    <section class="mb-6">
        <a href="{{ route('master.audit-log') }}" wire:navigate
           class="hero-gradient rounded-2xl p-5 text-white flex items-center gap-4 block active:scale-[0.98] transition-transform">
            <div class="relative z-10 flex items-center gap-4 w-full">
                <div class="icon-container bg-white/10 backdrop-blur-sm">
                    <span class="material-symbols-outlined text-xl">description</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold">Audit Log</p>
                    <p class="text-xs text-white/70 mt-0.5">Pantau semua perubahan master data.</p>
                </div>
                @php $totalLogs = \App\Models\AuditLog::count(); @endphp
                @if($totalLogs > 0)
                <span class="text-xs font-bold bg-white/20 px-2.5 py-1 rounded-full">{{ $totalLogs }}</span>
                @endif
                <span class="material-symbols-outlined text-white/70">chevron_right</span>
            </div>
        </a>
    </section>

    {{-- User Profile & Logout --}}
    <section class="mb-6">
        <h3 class="text-base font-bold font-headline mb-3">Akun Saya</h3>
        <div class="card-elevated overflow-hidden">
            <div class="p-4 flex items-center gap-3 border-b border-surface-container-high">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center text-white font-bold text-base">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-on-surface">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-primary-10 text-primary mt-1">
                        {{ ucfirst(auth()->user()->roles->first()?->name ?? 'user') }}
                    </span>
                </div>
            </div>
            <div class="p-1">
                <livewire:auth.logout />
            </div>
        </div>
    </section>
</div>
