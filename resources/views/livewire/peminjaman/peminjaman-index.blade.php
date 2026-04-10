<div class="animate-fade-in">
    {{-- Flash Messages --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="mb-4 flex items-center gap-3 bg-success-light border border-success/20 text-success px-4 py-3 rounded-2xl">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         class="mb-4 flex items-center gap-3 bg-danger-light border border-error/20 text-error px-4 py-3 rounded-2xl">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">error</span>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Hero Banner --}}
    <section class="pt-3 mb-6">
        <div class="hero-gradient rounded-2xl p-6 text-white">
            <div class="relative z-10">
                <p class="text-white/70 text-xs font-semibold uppercase tracking-widest">Peminjaman</p>
                <h2 class="text-xl font-bold font-headline mt-1">Kelola Alur Aset Anda</h2>
                <p class="text-white/70 text-sm mt-1">Pantau peminjaman aktif dan riwayat.</p>
                <a href="{{ route('peminjaman.create') }}" wire:navigate class="mt-4 bg-white text-primary px-5 py-2.5 rounded-xl text-sm font-bold inline-flex items-center gap-2 shadow-sm active:scale-95 transition-transform">
                    <span class="material-symbols-outlined text-sm">add_circle</span> Ajukan Peminjaman
                </a>
            </div>
        </div>
    </section>

    {{-- Peminjaman Aktif --}}
    <section class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-base font-bold font-headline">Peminjaman Aktif</h3>
            <span class="badge badge-info">{{ $peminjamanAktif->count() }} Berlangsung</span>
        </div>
        <div class="space-y-2.5 stagger-children">
            @forelse($peminjamanAktif as $pinjam)
            <div class="card-elevated p-4" wire:key="aktif-{{ $pinjam->id }}">
                <div class="flex items-start gap-3">
                    <div class="icon-container {{ $pinjam->isOverdue() ? 'bg-tertiary-10' : 'bg-primary-10' }}">
                        <span class="material-symbols-outlined {{ $pinjam->isOverdue() ? 'text-tertiary' : 'text-primary' }} text-xl">
                            {{ $pinjam->isOverdue() ? 'warning' : 'person' }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-on-surface truncate">{{ $pinjam->inventaris?->nama ?? 'Item' }}</p>
                        <p class="text-[11px] text-on-surface-variant mt-0.5">Peminjam: {{ $pinjam->user?->name }}</p>
                        <div class="flex items-center gap-2 mt-2 bg-surface-container-low px-3 py-1.5 rounded-lg">
                            <span class="material-symbols-outlined text-[14px] text-on-surface-variant">schedule</span>
                            <span class="text-[11px] font-bold {{ $pinjam->isOverdue() ? 'text-tertiary' : 'text-primary' }}">
                                Jatuh tempo: {{ $pinjam->tanggal_kembali_rencana->format('d M Y') }}
                                @if($pinjam->isOverdue())
                                    <span class="ml-1 text-tertiary">(Terlambat {{ $pinjam->tanggal_kembali_rencana->diffForHumans(null, true) }})</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                {{-- Kembalikan Button --}}
                <div class="mt-3 pt-3 border-t border-surface-container-high">
                    <button
                        wire:click="kembalikan({{ $pinjam->id }})"
                        x-on:click="$dispatch('confirm-modal', {
                            title: 'Kembalikan Aset?',
                            message: 'Konfirmasi pengembalian {{ addslashes($pinjam->inventaris?->nama ?? 'aset ini') }}. Aset akan kembali tersedia.',
                            icon: 'assignment_return',
                            iconColor: 'success',
                            confirmText: 'Ya, Kembalikan',
                            confirmColor: 'success',
                            action: () => $wire.kembalikan({{ $pinjam->id }})
                        })"
                        class="w-full py-2 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-all active:scale-[0.98]
                               {{ $pinjam->isOverdue() ? 'bg-tertiary-10 text-tertiary hover:bg-tertiary/20' : 'bg-success-light text-success hover:bg-success/20' }}">
                        <span class="material-symbols-outlined text-sm">assignment_return</span>
                        Kembalikan Sekarang
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-on-surface-variant">
                <div class="icon-container-lg bg-surface-container-high mx-auto mb-3">
                    <span class="material-symbols-outlined text-2xl opacity-40">assignment_return</span>
                </div>
                <p class="text-sm font-medium">Belum ada peminjaman aktif</p>
            </div>
            @endforelse
        </div>
    </section>

    {{-- Laporan Bulanan --}}
    <section class="mb-6">
        <h3 class="text-base font-bold font-headline mb-3">Laporan Bulanan</h3>
        <div class="card-elevated p-5">
            <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">{{ now()->translatedFormat('F Y') }}</p>
            <div class="grid grid-cols-2 gap-3 mt-3">
                <div class="bg-surface-container-low p-4 rounded-xl text-center">
                    <span class="text-[10px] font-bold text-on-surface-variant uppercase">Total Pinjam</span>
                    <div class="text-3xl font-extrabold text-on-surface font-headline mt-1 animate-count-up">{{ $totalPinjam }}</div>
                </div>
                <div class="bg-surface-container-low p-4 rounded-xl text-center">
                    <span class="text-[10px] font-bold text-on-surface-variant uppercase">Terlambat</span>
                    <div class="text-3xl font-extrabold text-tertiary font-headline mt-1 animate-count-up">{{ str_pad($terlambat, 2, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Riwayat Pengembalian --}}
    @if($peminjamanSelesai->isNotEmpty())
    <section class="mb-6">
        <h3 class="text-base font-bold font-headline mb-3">Riwayat Terakhir</h3>
        <div class="space-y-2 stagger-children">
            @foreach($peminjamanSelesai as $pinjam)
            <div class="card-elevated p-4 opacity-75" wire:key="selesai-{{ $pinjam->id }}">
                <div class="flex items-center gap-3">
                    <div class="icon-container bg-success-light">
                        <span class="material-symbols-outlined text-success text-xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-on-surface truncate">{{ $pinjam->inventaris?->nama ?? 'Item' }}</p>
                        <p class="text-[11px] text-on-surface-variant mt-0.5">
                            {{ $pinjam->user?->name }} •
                            Dikembalikan {{ $pinjam->tanggal_kembali_aktual?->format('d M Y') ?? '-' }}
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-success-light text-success shrink-0">
                        <span class="material-symbols-outlined text-[12px]">check</span>
                        Selesai
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
