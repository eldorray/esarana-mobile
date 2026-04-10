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
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-danger-light border border-error/20 text-error px-4 py-3 rounded-2xl">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">error</span>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Header --}}
    <section class="pt-3 mb-5">
        <a href="{{ route('laporan.index') }}" wire:navigate
           class="text-primary text-sm font-semibold flex items-center gap-1 mb-4 active:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Laporan
        </a>

        {{-- Status Banner --}}
        @php
            $bannerBg = match($laporan->status) {
                'baru'    => 'from-amber-500 to-orange-500',
                'proses'  => 'from-primary to-blue-600',
                'selesai' => 'from-green-500 to-emerald-600',
                default   => 'from-red-500 to-rose-600',
            };
            $statusIcon = match($laporan->status) {
                'baru'    => 'error',
                'proses'  => 'pending',
                'selesai' => 'check_circle',
                default   => 'cancel',
            };
            $statusLabel = match($laporan->status) {
                'baru'    => 'Baru',
                'proses'  => 'Sedang Diproses',
                'selesai' => 'Selesai',
                default   => 'Ditolak',
            };
        @endphp
        <div class="bg-gradient-to-r {{ $bannerBg }} rounded-2xl p-5 text-white mb-5">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">{{ $statusIcon }}</span>
                <div>
                    <p class="text-xs font-semibold text-white/70 uppercase tracking-widest">Status Laporan</p>
                    <p class="text-lg font-extrabold font-headline mt-0.5">{{ $statusLabel }}</p>
                </div>
            </div>
        </div>

        <h1 class="text-xl font-extrabold tracking-tight text-on-surface font-headline leading-snug">
            {{ $laporan->aset_lokasi }}
        </h1>
        <div class="flex items-center gap-2 mt-2">
            <span class="badge badge-info">{{ strtoupper($laporan->tipe) }}</span>
            @if($laporan->kategori_laporan)
            <span class="badge badge-info">{{ $laporan->kategori_laporan }}</span>
            @endif
            <span class="text-[11px] text-on-surface-variant">{{ $laporan->created_at->diffForHumans() }}</span>
        </div>
    </section>

    {{-- Foto Laporan --}}
    @if($laporan->foto)
    <section class="mb-5">
        <h3 class="text-sm font-bold font-headline mb-2">Foto Bukti</h3>
        <div class="w-full rounded-2xl overflow-hidden bg-surface-container-high">
            <img src="{{ Storage::url($laporan->foto) }}" alt="Foto laporan" class="w-full object-cover max-h-64">
        </div>
    </section>
    @endif

    {{-- Deskripsi --}}
    <section class="mb-5">
        <h3 class="text-sm font-bold font-headline mb-2">Deskripsi Masalah</h3>
        <div class="card-elevated p-4">
            <p class="text-sm text-on-surface leading-relaxed">{{ $laporan->deskripsi }}</p>
        </div>
    </section>

    {{-- Info Pelapor --}}
    <section class="mb-5">
        <h3 class="text-sm font-bold font-headline mb-2">Informasi Pelapor</h3>
        <div class="card-elevated overflow-hidden">
            <div class="divide-y divide-surface-container-high">
                <div class="flex items-center px-4 py-3">
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant mr-3">person</span>
                    <div>
                        <p class="text-[10px] text-on-surface-variant font-semibold uppercase tracking-wide">Pelapor</p>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">{{ $laporan->pelapor_name }}</p>
                    </div>
                </div>

                @if($laporan->kontak_pelapor)
                <div class="flex items-center px-4 py-3">
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant mr-3">phone</span>
                    <div>
                        <p class="text-[10px] text-on-surface-variant font-semibold uppercase tracking-wide">Kontak</p>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">{{ $laporan->kontak_pelapor }}</p>
                    </div>
                </div>
                @endif

                <div class="flex items-center px-4 py-3">
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant mr-3">calendar_today</span>
                    <div>
                        <p class="text-[10px] text-on-surface-variant font-semibold uppercase tracking-wide">Dilaporkan</p>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">{{ $laporan->created_at->format('d F Y, H:i') }} WIB</p>
                    </div>
                </div>

                <div class="flex items-center px-4 py-3">
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant mr-3">location_on</span>
                    <div>
                        <p class="text-[10px] text-on-surface-variant font-semibold uppercase tracking-wide">Aset / Lokasi</p>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">{{ $laporan->aset_lokasi }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Action Buttons (Admin/Supervisor only) --}}
    @if($canManage && $laporan->status !== 'selesai' && $laporan->status !== 'ditolak')
    <section class="mb-6">
        <h3 class="text-sm font-bold font-headline mb-3">Tindakan</h3>
        <div class="space-y-2">
            @if($laporan->status === 'baru')
            <button
                wire:click="updateStatus('proses')"
                wire:loading.attr="disabled"
                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-primary text-white text-sm font-bold active:scale-[0.98] transition-transform shadow-sm">
                <span class="material-symbols-outlined text-lg">play_arrow</span>
                Mulai Proses Laporan
            </button>
            <button
                x-on:click="$dispatch('confirm-modal', {
                    title: 'Tolak Laporan?',
                    message: 'Laporan ini akan ditandai sebagai ditolak.',
                    icon: 'block',
                    iconColor: 'error',
                    confirmText: 'Ya, Tolak',
                    confirmColor: 'error',
                    action: () => $wire.updateStatus('ditolak')
                })"
                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-danger-light text-error text-sm font-bold active:scale-[0.98] transition-transform">
                <span class="material-symbols-outlined text-lg">close</span>
                Tolak Laporan
            </button>
            @elseif($laporan->status === 'proses')
            <button
                x-on:click="$dispatch('confirm-modal', {
                    title: 'Selesaikan Laporan?',
                    message: 'Tandai laporan ini sebagai sudah selesai ditangani.',
                    icon: 'check_circle',
                    iconColor: 'success',
                    confirmText: 'Ya, Selesaikan',
                    confirmColor: 'success',
                    action: () => $wire.updateStatus('selesai')
                })"
                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-success text-white text-sm font-bold active:scale-[0.98] transition-transform shadow-sm">
                <span class="material-symbols-outlined text-lg">check_circle</span>
                Tandai Selesai
            </button>
            @endif
        </div>
    </section>
    @endif

</div>
