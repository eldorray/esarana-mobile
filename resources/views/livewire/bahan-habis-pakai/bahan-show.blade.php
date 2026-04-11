<div class="animate-fade-in">
    {{-- Header --}}
    <section class="pt-3 mb-5">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('bahan.index') }}" wire:navigate class="icon-container-sm bg-surface-container-high active:scale-95 transition-transform">
                <span class="material-symbols-outlined text-on-surface-variant text-lg">arrow_back</span>
            </a>
            <div class="flex-1 min-w-0">
                <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-widest">Bahan Habis Pakai</p>
                <h1 class="text-xl font-extrabold tracking-tight text-on-surface font-headline truncate">{{ $bahan->nama }}</h1>
            </div>
        </div>
    </section>

    {{-- Stock Status Card --}}
    <section class="mb-5">
        <div class="{{ $bahan->isStokRendah() ? 'bg-gradient-to-br from-tertiary to-tertiary-container' : 'hero-gradient' }} rounded-2xl p-6 text-white relative overflow-hidden">
            <div class="absolute right-0 bottom-0 w-32 h-32 bg-white/5 rounded-full -mr-10 -mb-10"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-white/70 text-xs font-semibold uppercase tracking-widest">Stok Tersedia</p>
                        <p class="text-4xl font-extrabold font-headline mt-1 tracking-tight">{{ $bahan->stok }}</p>
                        <p class="text-white/80 text-sm mt-0.5">{{ $bahan->satuan }}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center">
                        @if($bahan->kategori?->icon)
                        <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1;">{{ $bahan->kategori->icon }}</span>
                        @else
                        <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1;">inventory_2</span>
                        @endif
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="bg-white/20 rounded-full h-2.5 overflow-hidden">
                    @php
                        $pct = $bahan->stok_minimum > 0
                            ? min(($bahan->stok / ($bahan->stok_minimum * 3)) * 100, 100)
                            : 50;
                    @endphp
                    <div class="h-full bg-white/80 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>

                <div class="flex items-center justify-between mt-2">
                    <p class="text-white/60 text-xs">Minimum: {{ $bahan->stok_minimum }} {{ $bahan->satuan }}</p>
                    @if($bahan->isStokRendah())
                    <span class="inline-flex items-center gap-1 bg-white/20 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[12px]">warning</span>
                        Stok Kritis
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 bg-white/20 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[12px]">check_circle</span>
                        Cukup
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Info Cards --}}
    <section class="mb-5">
        <div class="grid grid-cols-2 gap-3">
            <div class="card-elevated p-4">
                <p class="text-[10px] font-semibold text-on-surface-variant uppercase tracking-widest mb-1">Harga Satuan</p>
                <p class="text-base font-extrabold text-on-surface font-headline">
                    Rp {{ number_format($bahan->harga_satuan, 0, ',', '.') }}
                </p>
                <p class="text-[10px] text-on-surface-variant mt-0.5">per {{ $bahan->satuan }}</p>
            </div>
            <div class="card-elevated p-4">
                <p class="text-[10px] font-semibold text-on-surface-variant uppercase tracking-widest mb-1">Total Nilai</p>
                <p class="text-base font-extrabold text-primary font-headline">
                    Rp {{ number_format($bahan->harga_satuan * $bahan->stok, 0, ',', '.') }}
                </p>
                <p class="text-[10px] text-on-surface-variant mt-0.5">{{ $bahan->stok }} × {{ $bahan->satuan }}</p>
            </div>
        </div>
    </section>

    {{-- Detail Information --}}
    <section class="mb-5">
        <h3 class="text-base font-bold font-headline mb-3">Informasi Detail</h3>
        <div class="card-elevated overflow-hidden">
            <div class="divide-y divide-surface-container-high">
                <div class="flex items-center gap-3 px-4 py-3.5">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px]">tag</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold">Kode Item</p>
                        <p class="text-sm font-semibold text-on-surface mt-0.5 font-mono">{{ $bahan->kode }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 px-4 py-3.5">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px]">category</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold">Kategori</p>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">{{ $bahan->kategori?->nama ?? '—' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 px-4 py-3.5">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px]">straighten</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold">Satuan</p>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">{{ $bahan->satuan }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 px-4 py-3.5">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px]">inventory</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold">Stok Minimum</p>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">{{ $bahan->stok_minimum }} {{ $bahan->satuan }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 px-4 py-3.5">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px]">calendar_today</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold">Terakhir Diperbarui</p>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">{{ $bahan->updated_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Location Information --}}
    <section class="mb-5">
        <h3 class="text-base font-bold font-headline mb-3">Lokasi Penyimpanan</h3>
        @if($bahan->ruangan)
        <div class="card-elevated overflow-hidden">
            {{-- Location Building --}}
            @if($bahan->ruangan->lokasi)
            <a href="{{ route('lokasi.show', $bahan->ruangan->lokasi) }}" wire:navigate
               class="flex items-center gap-3 px-4 py-4 border-b border-surface-container-high active:bg-surface-container-low transition-colors">
                <div class="icon-container bg-primary-10 shrink-0">
                    <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings: 'FILL' 1;">location_city</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold">Gedung / Lokasi</p>
                    <p class="text-sm font-bold text-on-surface mt-0.5">{{ $bahan->ruangan->lokasi->nama }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $bahan->ruangan->lokasi->alamat }}</p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-primary-10 text-primary shrink-0">
                    {{ strtoupper($bahan->ruangan->lokasi->tipe) }}
                </span>
                <span class="material-symbols-outlined text-on-surface-variant text-lg">chevron_right</span>
            </a>
            @endif

            {{-- Room --}}
            <div class="flex items-center gap-3 px-4 py-4">
                <div class="icon-container bg-surface-container-high shrink-0">
                    <span class="material-symbols-outlined text-on-surface-variant text-xl">meeting_room</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold">Ruangan</p>
                    <p class="text-sm font-bold text-on-surface mt-0.5">{{ $bahan->ruangan->nama }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">Lantai {{ $bahan->ruangan->lantai }}
                        @if($bahan->ruangan->kapasitas)
                        • Kapasitas {{ $bahan->ruangan->kapasitas }} orang
                        @endif
                    </p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider
                    {{ $bahan->ruangan->status === 'aktif' ? 'bg-success-light text-success' : 'bg-surface-container-high text-on-surface-variant' }}">
                    {{ ucfirst($bahan->ruangan->status) }}
                </span>
            </div>
        </div>
        @else
        <div class="card-elevated p-6 text-center text-on-surface-variant">
            <span class="material-symbols-outlined text-2xl opacity-40 block mb-2">location_off</span>
            <p class="text-sm font-medium">Lokasi belum ditentukan</p>
        </div>
        @endif
    </section>

    {{-- Metadata --}}
    <section class="mb-8">
        <p class="text-[11px] text-on-surface-variant text-center">
            Ditambahkan {{ $bahan->created_at->translatedFormat('d F Y') }}
        </p>
    </section>
</div>
