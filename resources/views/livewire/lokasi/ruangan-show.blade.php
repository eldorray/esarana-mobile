<div class="animate-fade-in">
    {{-- Header --}}
    <section class="pt-3 mb-5">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('lokasi.show', $ruangan->lokasi) }}" wire:navigate
               class="icon-container-sm bg-surface-container-high active:scale-95 transition-transform">
                <span class="material-symbols-outlined text-on-surface-variant text-lg">arrow_back</span>
            </a>
            <div class="flex-1 min-w-0">
                <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-widest truncate">
                    {{ $ruangan->lokasi->nama }}
                </p>
                <h1 class="text-xl font-extrabold tracking-tight text-on-surface font-headline truncate">
                    {{ $ruangan->nama }}
                </h1>
            </div>
            {{-- QR Button --}}
            <button wire:click="generateQr"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    class="icon-container-sm bg-primary-10 active:scale-95 transition-transform shrink-0"
                    title="Generate QR Code">
                <span wire:loading wire:target="generateQr"
                      class="material-symbols-outlined text-primary text-lg animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="generateQr"
                      class="material-symbols-outlined text-primary text-lg">qr_code_2</span>
            </button>
            {{-- Status badge --}}
            @if($ruangan->status === 'aktif')
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-success-light text-success shrink-0">
                <span class="w-1.5 h-1.5 rounded-full bg-success"></span> Aktif
            </span>
            @else
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-warning-light text-warning-amber shrink-0">
                Nonaktif
            </span>
            @endif
        </div>
    </section>

    {{-- QR Modal --}}
    @if($showQr)
    <div x-data
         class="fixed inset-0 z-50 flex items-end justify-center bg-black/50 backdrop-blur-sm px-4 pb-6"
         x-on:keydown.escape.window="$wire.set('showQr', false)">
        <div class="w-full max-w-sm bg-surface rounded-3xl shadow-2xl overflow-hidden animate-slide-up"
             x-on:click.outside="$wire.set('showQr', false)">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-5 pt-5 pb-3 border-b border-surface-container-high">
                <div>
                    <h3 class="text-base font-extrabold font-headline text-on-surface">QR Ruangan</h3>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">Scan untuk melihat isi ruangan secara offline</p>
                </div>
                <button wire:click="$set('showQr', false)"
                        class="icon-container-sm bg-surface-container-high active:scale-90 transition-transform">
                    <span class="material-symbols-outlined text-on-surface-variant text-lg">close</span>
                </button>
            </div>

            {{-- QR Image --}}
            <div class="flex flex-col items-center px-5 py-5 gap-4">
                <div class="bg-white p-3 rounded-2xl shadow-ambient">
                    <img src="{{ $qrDataUri }}"
                         alt="QR Code {{ $ruangan->nama }}"
                         class="w-56 h-56 block">
                </div>
                <div class="w-full text-center">
                    <p class="text-sm font-bold text-on-surface">{{ $ruangan->nama }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $ruangan->lokasi->nama }} • Lt. {{ $ruangan->lantai ?? '?' }}</p>
                </div>
            </div>

            {{-- Payload Preview --}}
            <div class="mx-5 mb-4">
                <details class="group">
                    <summary class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant cursor-pointer select-none list-none py-2">
                        <span class="material-symbols-outlined text-[16px] group-open:rotate-90 transition-transform">chevron_right</span>
                        Lihat isi payload teks
                    </summary>
                    <pre class="mt-2 p-3 bg-surface-container rounded-xl text-[10px] text-on-surface-variant font-mono overflow-x-auto whitespace-pre-wrap leading-relaxed">{{ $qrPayload }}</pre>
                </details>
            </div>

            {{-- Download Button --}}
            <div class="px-5 pb-5">
                <a href="{{ $qrDataUri }}"
                   download="QR-{{ Str::slug($ruangan->nama) }}.png"
                   class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">download</span>
                    Simpan QR Code
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Info Cards --}}
    <section class="mb-5">
        <div class="grid grid-cols-3 gap-3">
            <div class="card-elevated p-3.5 text-center">
                <p class="text-2xl font-extrabold font-headline text-on-surface">{{ $ruangan->lantai ?? '—' }}</p>
                <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider mt-0.5">Lantai</p>
            </div>
            <div class="card-elevated p-3.5 text-center">
                <p class="text-2xl font-extrabold font-headline text-primary">{{ $ruangan->inventaris->count() }}</p>
                <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider mt-0.5">Aset</p>
            </div>
            <div class="card-elevated p-3.5 text-center">
                <p class="text-2xl font-extrabold font-headline text-tertiary">{{ $ruangan->bahanHabisPakais->count() }}</p>
                <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider mt-0.5">Bahan</p>
            </div>
        </div>
        @if($ruangan->kapasitas)
        <p class="text-[11px] text-on-surface-variant text-center mt-2">
            Kapasitas {{ $ruangan->kapasitas }} orang
        </p>
        @endif
    </section>

    {{-- Aset Tetap --}}
    <section class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-base font-bold font-headline">Aset Tetap</h3>
            <span class="text-[11px] text-on-surface-variant font-semibold">{{ $ruangan->inventaris->count() }} item</span>
        </div>

        @if($ruangan->inventaris->isEmpty())
        <div class="card-elevated p-8 text-center text-on-surface-variant">
            <span class="material-symbols-outlined text-2xl opacity-40 block mb-2">inventory_2</span>
            <p class="text-sm font-medium">Tidak ada aset di ruangan ini</p>
        </div>
        @else
        <div class="space-y-2 stagger-children">
            @foreach($ruangan->inventaris as $item)
            <a href="{{ route('inventaris.show', $item) }}" wire:navigate wire:key="inv-{{ $item->id }}"
               class="card-elevated p-4 flex items-center gap-3 block active:scale-[0.98] transition-transform">
                <div class="icon-container bg-primary-10 shrink-0">
                    @if($item->kategori?->icon)
                    <span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings: 'FILL' 1;">{{ $item->kategori->icon }}</span>
                    @else
                    <span class="material-symbols-outlined text-primary text-lg">inventory_2</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-on-surface truncate">{{ $item->nama }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">
                        {{ $item->kode }}
                        @if($item->kategori) • {{ $item->kategori->nama }} @endif
                    </p>
                </div>
                @if($item->status === 'tersedia')
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-success-light text-success shrink-0">Tersedia</span>
                @elseif($item->status === 'dipinjam')
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-primary-10 text-primary shrink-0">
                    <span class="w-1 h-1 rounded-full bg-primary animate-pulse"></span> Dipinjam
                </span>
                @elseif($item->status === 'maintenance')
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-warning-light text-warning-amber shrink-0">Maint.</span>
                @else
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-danger-light text-error shrink-0">Rusak</span>
                @endif
                <span class="material-symbols-outlined text-on-surface-variant text-lg shrink-0">chevron_right</span>
            </a>
            @endforeach
        </div>
        @endif
    </section>

    {{-- Bahan Habis Pakai --}}
    <section class="mb-8">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-base font-bold font-headline">Bahan Habis Pakai</h3>
            <span class="text-[11px] text-on-surface-variant font-semibold">{{ $ruangan->bahanHabisPakais->count() }} item</span>
        </div>

        @if($ruangan->bahanHabisPakais->isEmpty())
        <div class="card-elevated p-8 text-center text-on-surface-variant">
            <span class="material-symbols-outlined text-2xl opacity-40 block mb-2">science</span>
            <p class="text-sm font-medium">Tidak ada bahan habis pakai di ruangan ini</p>
        </div>
        @else
        <div class="space-y-2 stagger-children">
            @foreach($ruangan->bahanHabisPakais as $bahan)
            <a href="{{ route('inventaris.bahan.show', $bahan) }}" wire:navigate wire:key="bahan-{{ $bahan->id }}"
               class="card-elevated p-4 flex items-center gap-3 block active:scale-[0.98] transition-transform">
                <div class="icon-container {{ $bahan->isStokRendah() ? 'bg-tertiary-10' : 'bg-primary-10' }} shrink-0">
                    @if($bahan->kategori?->icon)
                    <span class="material-symbols-outlined text-lg {{ $bahan->isStokRendah() ? 'text-tertiary' : 'text-primary' }}" style="font-variation-settings: 'FILL' 1;">{{ $bahan->kategori->icon }}</span>
                    @else
                    <span class="material-symbols-outlined text-lg {{ $bahan->isStokRendah() ? 'text-tertiary' : 'text-primary' }}">inventory_2</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-on-surface truncate">{{ $bahan->nama }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">
                        {{ $bahan->kode }}
                        @if($bahan->kategori) • {{ $bahan->kategori->nama }} @endif
                    </p>
                    <div class="mt-1.5 progress-track">
                        <div class="{{ $bahan->isStokRendah() ? 'progress-fill-danger' : 'progress-fill' }}"
                             style="width: {{ $bahan->stok_minimum > 0 ? min(($bahan->stok / ($bahan->stok_minimum * 3)) * 100, 100) : 50 }}%"></div>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <span class="text-lg font-extrabold font-headline {{ $bahan->isStokRendah() ? 'text-tertiary' : 'text-on-surface' }}">{{ $bahan->stok }}</span>
                    <span class="text-[10px] text-on-surface-variant block uppercase">{{ $bahan->satuan }}</span>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant text-lg shrink-0">chevron_right</span>
            </a>
            @endforeach
        </div>
        @endif
    </section>
</div>
