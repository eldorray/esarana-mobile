<div class="animate-fade-in">
    <section class="pt-3 mb-5">
        <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-widest">Logistik</p>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline mt-1">Bahan Habis Pakai</h1>
    </section>

    @if($stokKritis > 0)
    <div class="bg-gradient-to-r from-tertiary to-tertiary-container rounded-2xl p-4 text-white mb-5 flex items-center gap-3 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-16 h-16 bg-white/5 rounded-full -mr-6 -mt-6"></div>
        <div class="icon-container bg-white/15 backdrop-blur-sm relative z-10">
            <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">warning</span>
        </div>
        <div class="relative z-10">
            <p class="text-sm font-bold">{{ $stokKritis }} item stok kritis</p>
            <p class="text-xs text-white/70">Segera lakukan pengadaan</p>
        </div>
    </div>
    @endif

    <div class="mb-5">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari bahan..." class="input-precision pl-11">
        </div>
    </div>

    <div class="space-y-2.5 stagger-children">
        @forelse($bahans as $bahan)
        <div class="card-elevated p-4">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-on-surface">{{ $bahan->nama }}</h3>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $bahan->kode }} • {{ $bahan->kategori?->nama ?? 'Umum' }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xl font-extrabold font-headline {{ $bahan->isStokRendah() ? 'text-tertiary' : 'text-on-surface' }}">{{ $bahan->stok }}</span>
                    <span class="text-[10px] text-on-surface-variant block uppercase">{{ $bahan->satuan }}</span>
                </div>
            </div>
            <div class="mt-3 progress-track">
                <div class="{{ $bahan->isStokRendah() ? 'progress-fill-danger' : 'progress-fill' }}"
                     style="width: {{ $bahan->stok_minimum > 0 ? min(($bahan->stok / ($bahan->stok_minimum * 3)) * 100, 100) : 50 }}%"></div>
            </div>
            @if($bahan->isStokRendah())
            <div class="mt-2.5 flex items-center gap-1.5 text-tertiary bg-tertiary-10 px-3 py-1.5 rounded-lg">
                <span class="material-symbols-outlined text-[14px]">warning</span>
                <span class="text-[11px] font-bold">Min: {{ $bahan->stok_minimum }} {{ $bahan->satuan }}</span>
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-16 text-on-surface-variant">
            <div class="icon-container-lg bg-surface-container-high mx-auto mb-3">
                <span class="material-symbols-outlined text-2xl opacity-40">science</span>
            </div>
            <p class="text-sm font-medium">Belum ada bahan habis pakai</p>
        </div>
        @endforelse
    </div>
</div>
