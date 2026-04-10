<div class="animate-fade-in">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         class="mb-4 flex items-center gap-3 bg-success-light border border-success/20 text-success px-4 py-3 rounded-2xl">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Header --}}
    <section class="pt-3 mb-5">
        <a href="{{ route('inventaris.index') }}" wire:navigate class="text-primary text-sm font-semibold flex items-center gap-1 mb-4 active:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Inventaris
        </a>

        {{-- Foto --}}
        @if($inventaris->gambar)
        <div class="w-full h-52 rounded-2xl overflow-hidden mb-4 bg-surface-container-high">
            <img src="{{ Storage::url($inventaris->gambar) }}" alt="{{ $inventaris->nama }}" class="w-full h-full object-cover">
        </div>
        @else
        <div class="w-full h-40 rounded-2xl bg-surface-container-high flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant opacity-30">inventory_2</span>
        </div>
        @endif

        {{-- Nama & Status --}}
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="badge badge-info">{{ $inventaris->kategori?->nama ?? 'Umum' }}</span>
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline">{{ $inventaris->nama }}</h1>
                <p class="text-on-surface-variant text-sm mt-1">{{ $inventaris->kode }}</p>
            </div>
            @php
                $statusColor = match($inventaris->status) {
                    'tersedia' => 'bg-success-light text-success',
                    'dipinjam' => 'bg-primary-10 text-primary',
                    'maintenance' => 'bg-warning-light text-warning-amber',
                    default => 'bg-danger-light text-error',
                };
                $statusLabel = match($inventaris->status) {
                    'tersedia' => 'Tersedia',
                    'dipinjam' => 'Dipinjam',
                    'maintenance' => 'Maintenance',
                    default => 'Rusak',
                };
            @endphp
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $statusColor }} shrink-0">
                {{ $statusLabel }}
            </span>
        </div>
    </section>

    {{-- Info Cards --}}
    <section class="mb-5">
        <div class="grid grid-cols-2 gap-3">
            <div class="card-elevated p-4">
                <p class="text-[10px] font-semibold text-on-surface-variant uppercase tracking-widest">Nilai Aset</p>
                <p class="text-lg font-extrabold text-on-surface font-headline mt-1">
                    Rp {{ number_format($inventaris->nilai_aset ?? 0, 0, ',', '.') }}
                </p>
            </div>
            <div class="card-elevated p-4">
                <p class="text-[10px] font-semibold text-on-surface-variant uppercase tracking-widest">Kondisi</p>
                <p class="text-base font-extrabold text-on-surface font-headline mt-1 capitalize">
                    {{ str_replace('_', ' ', $inventaris->kondisi ?? '-') }}
                </p>
            </div>
        </div>
    </section>

    {{-- Detail Info --}}
    <section class="mb-5">
        <h3 class="text-base font-bold font-headline mb-3">Detail Barang</h3>
        <div class="card-elevated overflow-hidden">
            @if($inventaris->deskripsi)
            <div class="p-4 border-b border-surface-container-high">
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Deskripsi</p>
                <p class="text-sm text-on-surface leading-relaxed">{{ $inventaris->deskripsi }}</p>
            </div>
            @endif

            <div class="divide-y divide-surface-container-high">
                <div class="flex items-center px-4 py-3">
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant mr-3">qr_code</span>
                    <div class="flex-1">
                        <p class="text-[10px] text-on-surface-variant font-semibold uppercase tracking-wide">Serial Number</p>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">{{ $inventaris->serial_number ?: '-' }}</p>
                    </div>
                </div>

                <div class="flex items-center px-4 py-3">
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant mr-3">location_on</span>
                    <div class="flex-1">
                        <p class="text-[10px] text-on-surface-variant font-semibold uppercase tracking-wide">Lokasi</p>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">
                            {{ $inventaris->ruangan?->lokasi?->nama ?? '-' }}
                            @if($inventaris->ruangan)
                            <span class="text-on-surface-variant font-normal"> • {{ $inventaris->ruangan->nama }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center px-4 py-3">
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant mr-3">category</span>
                    <div class="flex-1">
                        <p class="text-[10px] text-on-surface-variant font-semibold uppercase tracking-wide">Kategori</p>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">{{ $inventaris->kategori?->nama ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-center px-4 py-3">
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant mr-3">calendar_today</span>
                    <div class="flex-1">
                        <p class="text-[10px] text-on-surface-variant font-semibold uppercase tracking-wide">Tanggal Perolehan</p>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">
                            {{ $inventaris->tanggal_perolehan?->format('d F Y') ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Riwayat Peminjaman --}}
    <section class="mb-6">
        <h3 class="text-base font-bold font-headline mb-3">Riwayat Peminjaman</h3>
        @if($inventaris->peminjamans->isEmpty())
        <div class="card-elevated p-8 text-center text-on-surface-variant">
            <span class="material-symbols-outlined text-3xl opacity-30 mb-2 block">history</span>
            <p class="text-sm font-medium">Belum pernah dipinjam</p>
        </div>
        @else
        <div class="card-elevated overflow-hidden">
            @foreach($inventaris->peminjamans->sortByDesc('created_at') as $pinjam)
            <div class="p-4 flex items-center gap-3 {{ !$loop->last ? 'border-b border-surface-container-high' : '' }}">
                <div class="icon-container-sm {{ $pinjam->status === 'aktif' ? 'bg-primary-10' : 'bg-success-light' }}">
                    <span class="material-symbols-outlined text-lg {{ $pinjam->status === 'aktif' ? 'text-primary' : 'text-success' }}" style="font-variation-settings: 'FILL' 1;">
                        {{ $pinjam->status === 'aktif' ? 'arrow_outward' : 'assignment_return' }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-on-surface">{{ $pinjam->user?->name ?? 'Unknown' }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">
                        {{ $pinjam->tanggal_pinjam->format('d M Y') }}
                        @if($pinjam->tanggal_kembali_aktual)
                            → {{ $pinjam->tanggal_kembali_aktual->format('d M Y') }}
                        @else
                            → Rencana {{ $pinjam->tanggal_kembali_rencana->format('d M Y') }}
                        @endif
                    </p>
                </div>
                @if($pinjam->status === 'aktif')
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-primary-10 text-primary">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                    Aktif
                </span>
                @else
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-success-light text-success">
                    <span class="material-symbols-outlined text-[10px]">check</span>
                    Selesai
                </span>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </section>

    {{-- Action Button (Edit) --}}
    @can('manage_inventaris')
    <div class="pb-4">
        <a href="{{ route('inventaris.edit', $inventaris) }}" wire:navigate
           class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-sm">edit</span>
            Edit Data Inventaris
        </a>
    </div>
    @endcan

</div>
