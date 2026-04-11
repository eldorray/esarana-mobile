<div class="animate-fade-in">
    <section class="pt-3 mb-5">
        <div class="flex items-center gap-3 mb-1">
            <a href="{{ route('inventaris.index') }}" wire:navigate class="icon-container-sm bg-surface-container-high active:scale-95 transition-transform">
                <span class="material-symbols-outlined text-on-surface-variant text-lg">arrow_back</span>
            </a>
            <div>
                <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-widest">Inventaris</p>
                <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline">Bahan Habis Pakai</h1>
            </div>
        </div>
    </section>

    {{-- Flash --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
         class="mb-4 flex items-center gap-3 bg-success-light border border-success/20 text-success px-4 py-3 rounded-2xl">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-danger-light border border-error/20 text-error px-4 py-3 rounded-2xl">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">error</span>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    @if($stokKritis > 0)
    <div class="bg-gradient-to-r from-tertiary to-tertiary-container rounded-2xl p-4 text-white mb-5 flex items-center gap-3 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-16 h-16 bg-white/5 rounded-full -mr-6 -mt-6"></div>
        <div class="icon-container bg-white/15 backdrop-blur-sm relative z-10">
            <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">warning</span>
        </div>
        <div class="flex-1 relative z-10">
            <p class="text-sm font-bold">{{ $stokKritis }} item stok kritis</p>
            <p class="text-xs text-white/70">Segera lakukan pengadaan</p>
        </div>
    </div>
    @endif

    <div class="flex items-center gap-3 mb-5">
        <div class="relative flex-1">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau kode..." class="input-precision pl-11">
        </div>
        @can('manage_inventaris')
        <a href="{{ route('inventaris.bahan.create') }}" wire:navigate class="btn-primary px-4 py-2.5 text-sm flex items-center gap-1.5 shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah
        </a>
        @endcan
    </div>

    <div class="space-y-2.5 stagger-children">
        @forelse($bahans as $bahan)
        <div class="card-elevated p-4" wire:key="bahan-{{ $bahan->id }}">
            <a href="{{ route('inventaris.bahan.show', $bahan) }}" wire:navigate class="flex items-start gap-3 block">
                <div class="icon-container {{ $bahan->isStokRendah() ? 'bg-tertiary-10' : 'bg-primary-10' }} shrink-0">
                    @if($bahan->kategori?->icon)
                    <span class="material-symbols-outlined text-lg {{ $bahan->isStokRendah() ? 'text-tertiary' : 'text-primary' }}" style="font-variation-settings: 'FILL' 1;">{{ $bahan->kategori->icon }}</span>
                    @else
                    <span class="material-symbols-outlined text-lg {{ $bahan->isStokRendah() ? 'text-tertiary' : 'text-primary' }}">inventory_2</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start gap-2">
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-on-surface truncate">{{ $bahan->nama }}</h3>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">
                                {{ $bahan->kode }}
                                @if($bahan->kategori) • {{ $bahan->kategori->nama }} @endif
                            </p>
                            @if($bahan->ruangan)
                            <p class="text-[11px] text-on-surface-variant mt-0.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">location_on</span>
                                {{ $bahan->ruangan->lokasi?->nama }} – {{ $bahan->ruangan->nama }}
                            </p>
                            @endif
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-xl font-extrabold font-headline {{ $bahan->isStokRendah() ? 'text-tertiary' : 'text-on-surface' }}">{{ $bahan->stok }}</span>
                            <span class="text-[10px] text-on-surface-variant block uppercase">{{ $bahan->satuan }}</span>
                        </div>
                    </div>
                    <div class="mt-2.5 progress-track">
                        <div class="{{ $bahan->isStokRendah() ? 'progress-fill-danger' : 'progress-fill' }}"
                             style="width: {{ $bahan->stok_minimum > 0 ? min(($bahan->stok / ($bahan->stok_minimum * 3)) * 100, 100) : 50 }}%"></div>
                    </div>
                    @if($bahan->isStokRendah())
                    <div class="mt-2 flex items-center gap-1.5 text-tertiary bg-tertiary-10 px-2.5 py-1.5 rounded-lg">
                        <span class="material-symbols-outlined text-[13px]">warning</span>
                        <span class="text-[11px] font-bold">Stok kritis — min {{ $bahan->stok_minimum }} {{ $bahan->satuan }}</span>
                    </div>
                    @endif
                </div>
            </a>

            {{-- Actions --}}
            @can('manage_inventaris')
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-surface-container-high">
                <a href="{{ route('inventaris.bahan.edit', $bahan) }}" wire:navigate
                   class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-primary-10 text-primary text-xs font-bold active:scale-95 transition-transform">
                    <span class="material-symbols-outlined text-[16px]">edit</span> Edit
                </a>
                <button
                    x-on:click="$dispatch('confirm-modal', {
                        title: 'Hapus Item?',
                        message: '{{ addslashes($bahan->nama) }} akan dihapus secara permanen.',
                        icon: 'delete',
                        iconColor: 'error',
                        confirmText: 'Ya, Hapus',
                        confirmColor: 'error',
                        action: () => $wire.hapus({{ $bahan->id }})
                    })"
                    class="flex items-center justify-center gap-1 px-3 py-2 rounded-xl bg-danger-light text-error text-xs font-bold active:scale-95 transition-transform">
                    <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
                </button>
            </div>
            @endcan
        </div>
        @empty
        <div class="text-center py-16 text-on-surface-variant">
            <div class="icon-container-lg bg-surface-container-high mx-auto mb-3">
                <span class="material-symbols-outlined text-2xl opacity-40">science</span>
            </div>
            <p class="text-sm font-medium">Belum ada bahan habis pakai</p>
            @can('manage_inventaris')
            <a href="{{ route('inventaris.bahan.create') }}" wire:navigate class="btn-primary px-5 py-2.5 text-sm inline-flex items-center gap-2 mt-4">
                <span class="material-symbols-outlined text-sm">add</span> Tambah Pertama
            </a>
            @endcan
        </div>
        @endforelse
    </div>
</div>
