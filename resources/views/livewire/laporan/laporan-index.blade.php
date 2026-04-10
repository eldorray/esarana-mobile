<div class="animate-fade-in">
    <section class="pt-3 mb-5">
        <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-widest">Pelaporan</p>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline mt-1">Laporan Masalah</h1>
    </section>

    <div class="flex justify-end mb-5">
        <a href="{{ route('laporan.create') }}" wire:navigate class="btn-primary px-5 py-2.5 text-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">add</span> Buat Laporan
        </a>
    </div>

    <div class="space-y-2.5 stagger-children">
        @forelse($laporans as $lap)
        <div class="card-elevated p-4" wire:key="lap-{{ $lap->id }}">
            <a href="{{ route('laporan.show', $lap) }}" wire:navigate class="flex items-start gap-3 block">
                <div class="icon-container {{ $lap->status === 'baru' ? 'bg-tertiary-10' : ($lap->status === 'proses' ? 'bg-primary-10' : ($lap->status === 'selesai' ? 'bg-success-light' : 'bg-danger-light')) }} shrink-0">
                    <span class="material-symbols-outlined text-lg {{ $lap->status === 'baru' ? 'text-tertiary' : ($lap->status === 'proses' ? 'text-primary' : ($lap->status === 'selesai' ? 'text-success' : 'text-error')) }}" style="font-variation-settings: 'FILL' 1;">
                        {{ $lap->status === 'baru' ? 'error' : ($lap->status === 'proses' ? 'pending' : ($lap->status === 'selesai' ? 'check_circle' : 'cancel')) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <p class="text-sm font-bold text-on-surface truncate pr-2">{{ $lap->aset_lokasi }}</p>
                        @if($lap->status === 'baru')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-tertiary-10 text-tertiary shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary animate-pulse"></span>
                            Baru
                        </span>
                        @elseif($lap->status === 'proses')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-primary-10 text-primary shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                            Proses
                        </span>
                        @elseif($lap->status === 'selesai')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-success-light text-success shrink-0">
                            <span class="material-symbols-outlined text-[12px]">check</span>
                            Selesai
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-danger-light text-error shrink-0">
                            Ditolak
                        </span>
                        @endif
                    </div>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $lap->pelapor_name }} • {{ $lap->created_at->diffForHumans() }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge badge-info">{{ strtoupper($lap->tipe) }}</span>
                    </div>
                    <p class="text-sm text-on-surface-variant mt-2 line-clamp-2">{{ $lap->deskripsi }}</p>
                </div>
            </a>

            {{-- Action Buttons (Admin/Supervisor only) — di luar link agar tidak nested --}}
            @if($canManage && $lap->status !== 'selesai' && $lap->status !== 'ditolak')
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-surface-container-high">
                @if($lap->status === 'baru')
                <button wire:click="updateStatus({{ $lap->id }}, 'proses')" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-primary-10 text-primary text-xs font-bold active:scale-95 transition-transform">
                    <span class="material-symbols-outlined text-[16px]">play_arrow</span> Proses
                </button>
                <button
                    x-on:click="$dispatch('confirm-modal', {
                        title: 'Tolak Laporan?',
                        message: 'Laporan ini akan ditandai sebagai ditolak.',
                        icon: 'block',
                        iconColor: 'error',
                        confirmText: 'Ya, Tolak',
                        confirmColor: 'error',
                        action: () => $wire.updateStatus({{ $lap->id }}, 'ditolak')
                    })"
                    class="flex items-center justify-center gap-1 px-3 py-2 rounded-xl bg-danger-light text-error text-xs font-bold active:scale-95 transition-transform">
                    <span class="material-symbols-outlined text-[16px]">close</span> Tolak
                </button>
                @elseif($lap->status === 'proses')
                <button wire:click="updateStatus({{ $lap->id }}, 'selesai')" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-success-light text-success text-xs font-bold active:scale-95 transition-transform">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span> Selesaikan
                </button>
                @endif
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-16 text-on-surface-variant">
            <div class="icon-container-lg bg-surface-container-high mx-auto mb-3">
                <span class="material-symbols-outlined text-2xl opacity-40">report</span>
            </div>
            <p class="text-sm font-medium">Belum ada laporan</p>
        </div>
        @endforelse
    </div>
</div>
