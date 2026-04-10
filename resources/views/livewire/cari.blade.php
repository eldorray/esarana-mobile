<div class="animate-fade-in">
    <section class="pt-3 mb-5">
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline mb-4">Pencarian</h1>

        {{-- Search Input --}}
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
            <input
                wire:model.live.debounce.300ms="query"
                type="search"
                placeholder="Cari aset, bahan, laporan..."
                class="input-precision pl-11 pr-10"
                autofocus>
            @if($query)
            <button wire:click="$set('query', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
            @endif
        </div>
    </section>

    {{-- Empty state --}}
    @if(strlen($query) < 2)
    <div class="text-center py-16 text-on-surface-variant">
        <span class="material-symbols-outlined text-5xl opacity-30 mb-3 block">manage_search</span>
        <p class="text-sm font-medium">Ketik minimal 2 karakter untuk mencari</p>
        <p class="text-xs text-on-surface-variant mt-1">Cari aset, bahan habis pakai, atau laporan</p>
    </div>
    @elseif($totalHasil === 0)
    <div class="text-center py-16 text-on-surface-variant">
        <span class="material-symbols-outlined text-5xl opacity-30 mb-3 block">search_off</span>
        <p class="text-sm font-medium">Tidak ada hasil untuk "{{ $query }}"</p>
        <p class="text-xs mt-1">Coba kata kunci lain</p>
    </div>
    @else

    <p class="text-xs text-on-surface-variant mb-4">
        Ditemukan <span class="font-bold text-primary">{{ $totalHasil }}</span> hasil untuk "<span class="font-semibold">{{ $query }}</span>"
    </p>

    {{-- Inventaris Results --}}
    @if($inventaris->isNotEmpty())
    <section class="mb-5">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-primary text-lg">inventory_2</span>
            <h3 class="text-sm font-bold text-on-surface">Aset Inventaris ({{ $inventaris->count() }})</h3>
        </div>
        <div class="space-y-2 stagger-children">
            @foreach($inventaris as $item)
            <a href="{{ route('inventaris.show', $item) }}" wire:navigate class="card-elevated p-4 flex items-center gap-3 block active:scale-[0.98] transition-transform">
                <div class="icon-container bg-primary-10 shrink-0">
                    <span class="material-symbols-outlined text-primary text-xl">inventory_2</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-on-surface truncate">{{ $item->nama }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">
                        {{ $item->kode }} • {{ $item->ruangan?->lokasi?->nama ?? 'Lokasi belum diset' }}
                    </p>
                </div>
                @php
                    $statusColor = match($item->status) {
                        'tersedia' => 'bg-success-light text-success',
                        'dipinjam' => 'bg-primary-10 text-primary',
                        'maintenance' => 'bg-warning-light text-warning-amber',
                        default => 'bg-danger-light text-error',
                    };
                @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $statusColor }} shrink-0">
                    {{ $item->status }}
                </span>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Bahan Habis Pakai Results --}}
    @if($bahanHabisPakai->isNotEmpty())
    <section class="mb-5">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-tertiary text-lg">science</span>
            <h3 class="text-sm font-bold text-on-surface">Bahan Habis Pakai ({{ $bahanHabisPakai->count() }})</h3>
        </div>
        <div class="space-y-2 stagger-children">
            @foreach($bahanHabisPakai as $bahan)
            <a href="{{ route('bahan.index') }}" wire:navigate class="card-elevated p-4 flex items-center gap-3 block active:scale-[0.98] transition-transform">
                <div class="icon-container bg-tertiary-10 shrink-0">
                    <span class="material-symbols-outlined text-tertiary text-xl">science</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-on-surface truncate">{{ $bahan->nama }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $bahan->kode }} • Stok: {{ $bahan->stok }} {{ $bahan->satuan }}</p>
                </div>
                @if($bahan->isStokRendah())
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-tertiary-10 text-tertiary shrink-0">
                    <span class="material-symbols-outlined text-[10px]">warning</span>
                    Kritis
                </span>
                @endif
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Laporan Results --}}
    @if($laporans->isNotEmpty())
    <section class="mb-5">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-error text-lg">report</span>
            <h3 class="text-sm font-bold text-on-surface">Laporan ({{ $laporans->count() }})</h3>
        </div>
        <div class="space-y-2 stagger-children">
            @foreach($laporans as $laporan)
            <a href="{{ route('laporan.index') }}" wire:navigate class="card-elevated p-4 flex items-center gap-3 block active:scale-[0.98] transition-transform">
                <div class="icon-container bg-danger-light shrink-0">
                    <span class="material-symbols-outlined text-error text-xl" style="font-variation-settings: 'FILL' 1;">report</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-on-surface truncate">{{ $laporan->aset_lokasi }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $laporan->pelapor_name }} • {{ $laporan->created_at->diffForHumans() }}</p>
                </div>
                @php
                    $sColor = match($laporan->status) {
                        'baru' => 'bg-tertiary-10 text-tertiary',
                        'proses' => 'bg-primary-10 text-primary',
                        'selesai' => 'bg-success-light text-success',
                        default => 'bg-danger-light text-error',
                    };
                @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $sColor }} shrink-0">
                    {{ $laporan->status }}
                </span>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    @endif
</div>
