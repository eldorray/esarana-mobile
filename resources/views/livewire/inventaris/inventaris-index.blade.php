<div class="animate-fade-in">
    <section class="pt-3 mb-5">
        <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-widest">Modul Inventaris</p>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline mt-1">Inventaris & Bahan</h1>
    </section>

    {{-- Tab Switcher --}}
    <div class="flex gap-2 mb-5">
        <button wire:click="$set('tab', 'aset')" class="chip {{ $tab === 'aset' ? 'chip-active' : '' }}">
            <span class="material-symbols-outlined text-[16px]">inventory_2</span> Aset Tetap
        </button>
        <a href="{{ route('inventaris.bahan.index') }}" wire:navigate class="chip {{ $tab === 'bahan' ? 'chip-active' : '' }}">
            <span class="material-symbols-outlined text-[16px]">science</span> Bahan Habis Pakai
        </a>
    </div>

    {{-- Search --}}
    <div class="mb-5">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari aset atau bahan..." class="input-precision pl-11">
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-3 mb-5">
        <div class="stat-card">
            <span class="text-[10px] font-semibold text-on-surface-variant uppercase tracking-widest">Total Aset</span>
            <div class="text-2xl font-extrabold text-on-surface font-headline mt-1">{{ $totalAset }}</div>
        </div>
        <div class="stat-card border-l-tertiary">
            <span class="text-[10px] font-semibold text-on-surface-variant uppercase tracking-widest">Perlu Maintenance</span>
            <div class="text-2xl font-extrabold text-tertiary font-headline mt-1">{{ str_pad($perluMaintenance, 2, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>

    @if($tab === 'aset')
    {{-- Inventaris List --}}
    <div class="space-y-2.5 stagger-children">
        @forelse($inventaris as $item)
        <a href="{{ route('inventaris.show', $item) }}" wire:navigate wire:key="inv-{{ $item->id }}"
           class="card-elevated p-4 block active:scale-[0.98] transition-transform">
            <div class="flex justify-between items-start">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="badge badge-info">{{ $item->kategori?->nama ?? 'Umum' }}</span>
                    </div>
                    <h3 class="text-sm font-bold text-on-surface truncate">{{ $item->nama }}</h3>
                    <p class="text-[11px] text-on-surface-variant mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">location_on</span>
                        {{ $item->ruangan?->lokasi?->nama ?? 'Belum diset' }} • {{ $item->kode }}
                    </p>
                </div>
                @if($item->status === 'tersedia')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-success-light text-success shrink-0">Tersedia</span>
                @elseif($item->status === 'dipinjam')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-primary-10 text-primary shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    Dipinjam
                </span>
                @elseif($item->status === 'maintenance')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-warning-light text-warning-amber shrink-0">Maintenance</span>
                @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-danger-light text-error shrink-0">Rusak</span>
                @endif
            </div>
            @if($item->serial_number)
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-surface-container-high">
                <div class="flex gap-2">
                    <span class="badge badge-info">SN: {{ $item->serial_number }}</span>
                    @if($item->ruangan)
                    <span class="badge badge-info">{{ $item->ruangan->nama }}</span>
                    @endif
                </div>
                <span class="material-symbols-outlined text-[16px] text-on-surface-variant">chevron_right</span>
            </div>
            @else
            <div class="flex justify-end mt-2">
                <span class="material-symbols-outlined text-[16px] text-on-surface-variant">chevron_right</span>
            </div>
            @endif
        </a>
        @empty
        <div class="text-center py-16 text-on-surface-variant">
            <div class="icon-container-lg bg-surface-container-high mx-auto mb-3">
                <span class="material-symbols-outlined text-2xl opacity-40">inventory_2</span>
            </div>
            <p class="text-sm font-medium">Belum ada aset terdaftar</p>
        </div>
        @endforelse
    </div>
    @endif
</div>
