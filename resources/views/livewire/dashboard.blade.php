<div class="animate-fade-in">
    {{-- Welcome & Date --}}
    <section class="pt-3 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-on-surface-variant text-sm font-medium">{{ now()->translatedFormat('l, d F Y') }}</p>
                <h1 class="text-2xl font-extrabold text-on-surface tracking-tight font-headline mt-0.5">Halo, {{ auth()->user()->name }}!</h1>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-primary-10 text-primary">
                {{ ucfirst($roleName) }}
            </span>
        </div>
    </section>

    {{-- Hero Stats Card --}}
    <section class="mb-6">
        <div class="hero-gradient rounded-2xl p-6 text-white">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <p class="text-white/70 text-xs font-semibold uppercase tracking-widest">Total Nilai Aset</p>
                        <p class="text-3xl font-extrabold font-headline mt-1 tracking-tight">Rp {{ number_format($nilaiTotal / 1000000, 1) }}M</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                        <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1;">account_balance</span>
                    </div>
                </div>

                {{-- Mini Stats Row --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl px-3 py-2.5 text-center">
                        <p class="text-2xl font-extrabold font-headline">{{ number_format($totalAset) }}</p>
                        <p class="text-[10px] font-medium text-white/70 uppercase tracking-wider mt-0.5">Aset</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl px-3 py-2.5 text-center">
                        <p class="text-2xl font-extrabold font-headline">{{ str_pad($peminjamanAktif, 2, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-[10px] font-medium text-white/70 uppercase tracking-wider mt-0.5">Pinjaman</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl px-3 py-2.5 text-center">
                        <p class="text-2xl font-extrabold font-headline">{{ str_pad($permintaanBaru, 2, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-[10px] font-medium text-white/70 uppercase tracking-wider mt-0.5">Permintaan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Alert: Stok Kritis --}}
    @if($stokRendah > 0)
    <section class="mb-6 animate-slide-up">
        <div class="bg-gradient-to-r from-tertiary to-tertiary-container rounded-2xl p-4 text-white flex items-center gap-4 shadow-ambient relative overflow-hidden">
            <div class="absolute right-0 top-0 w-20 h-20 bg-white/5 rounded-full -mr-8 -mt-8"></div>
            <div class="icon-container bg-white/15 backdrop-blur-sm relative z-10">
                <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">warning</span>
            </div>
            <div class="flex-1 relative z-10">
                <p class="text-sm font-bold">{{ $stokRendah }} item stok kritis</p>
                <p class="text-xs text-white/70 mt-0.5">Perlu pengadaan segera</p>
            </div>
            <a href="{{ route('bahan.index') }}" wire:navigate class="bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors relative z-10">
                Lihat
            </a>
        </div>
    </section>
    @endif

    {{-- Quick Actions --}}
    <section class="mb-6">
        <div class="flex gap-3">
            <a href="{{ route('inventaris.create') }}" wire:navigate class="flex-1 card-elevated p-4 flex items-center gap-3 active:scale-[0.98] transition-transform">
                <div class="icon-container bg-primary-10">
                    <span class="material-symbols-outlined text-primary text-xl">add_box</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-on-surface">Aset Baru</p>
                    <p class="text-[11px] text-on-surface-variant">Tambah inventaris</p>
                </div>
            </a>
            <a href="{{ route('laporan.create') }}" wire:navigate class="flex-1 card-elevated p-4 flex items-center gap-3 active:scale-[0.98] transition-transform">
                <div class="icon-container bg-tertiary-10">
                    <span class="material-symbols-outlined text-tertiary text-xl">report</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-on-surface">Lapor</p>
                    <p class="text-[11px] text-on-surface-variant">Laporkan masalah</p>
                </div>
            </a>
        </div>
    </section>

    {{-- Sebaran Lokasi (Horizontal Scroll) — hanya admin/supervisor --}}
    @if(!$isStaff)
    <section class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-base font-bold font-headline">Sebaran Lokasi</h3>
            <a href="{{ route('lokasi.index') }}" wire:navigate class="text-primary font-semibold text-xs flex items-center gap-0.5 hover:underline">Semua <span class="material-symbols-outlined text-[16px]">chevron_right</span></a>
        </div>
        <div class="scroll-snap-x flex gap-3 -mx-5 px-5">
            @foreach($lokasis as $lokasi)
            <a href="{{ route('lokasi.show', $lokasi) }}" wire:navigate class="scroll-snap-item w-[70%] card-elevated p-4 active:scale-[0.98] transition-transform">
                <div class="flex items-center gap-3 mb-3">
                    <div class="icon-container-sm bg-primary-10">
                        <span class="material-symbols-outlined text-primary text-lg">location_on</span>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-primary-10 text-primary">{{ strtoupper($lokasi->tipe) }}</span>
                </div>
                <h4 class="font-bold text-on-surface text-sm">{{ $lokasi->nama }}</h4>
                <p class="text-xs text-on-surface-variant mt-1">{{ $lokasi->ruangans_count }} Ruangan tersedia</p>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Aktivitas Terbaru --}}
    <section class="mb-6">
        <h3 class="text-base font-bold font-headline mb-3">Aktivitas Terbaru</h3>
        <div class="space-y-2 stagger-children">
            @forelse($aktivitasTerbaru as $aktivitas)
            <a href="{{ $aktivitas->inventaris ? route('inventaris.show', $aktivitas->inventaris) : '#' }}" wire:navigate
               class="card-elevated p-3.5 flex items-center gap-3 block active:scale-[0.98] transition-transform">
                <div class="icon-container {{ $aktivitas->status === 'aktif' ? 'bg-primary-10 text-primary' : ($aktivitas->status === 'selesai' ? 'bg-success-light text-success' : 'bg-tertiary-10 text-tertiary') }}">
                    <span class="material-symbols-outlined text-lg">
                        {{ $aktivitas->status === 'aktif' ? 'arrow_outward' : 'assignment_return' }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-on-surface truncate">{{ $aktivitas->inventaris?->nama ?? 'Item' }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $aktivitas->user?->name }} • {{ $aktivitas->created_at->diffForHumans() }}</p>
                </div>
                @if($aktivitas->status === 'aktif')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-primary-10 text-primary">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                    Dipinjam
                </span>
                @elseif($aktivitas->status === 'selesai')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-success-light text-success">
                    <span class="material-symbols-outlined text-[12px]">check</span>
                    Kembali
                </span>
                @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-tertiary-10 text-tertiary">
                    <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                    Terlambat
                </span>
                @endif
            </a>
            @empty
            <div class="text-center py-10 text-on-surface-variant">
                <div class="icon-container-lg bg-surface-container-high mx-auto mb-3">
                    <span class="material-symbols-outlined text-2xl opacity-40">inbox</span>
                </div>
                <p class="text-sm font-medium">Belum ada aktivitas</p>
            </div>
            @endforelse
        </div>
    </section>

    {{-- Kategori Populer --}}
    <section class="mb-6">
        <h3 class="text-base font-bold font-headline mb-3">Kategori Populer</h3>
        <div class="card-elevated p-5 space-y-4">
            @foreach($kategoriPopuler as $kat)
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-sm font-medium text-on-surface">{{ $kat->nama }}</span>
                    <span class="text-sm font-bold text-primary">{{ $kat->inventaris_count }}</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" style="width: {{ $totalAset > 0 ? min(($kat->inventaris_count / max($totalAset, 1)) * 100 * 3, 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- Laporan Masuk --}}
    <section class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-base font-bold font-headline">Laporan Masuk</h3>
            <a href="{{ route('laporan.index') }}" wire:navigate class="text-primary font-semibold text-xs flex items-center gap-0.5 hover:underline">Semua <span class="material-symbols-outlined text-[16px]">chevron_right</span></a>
        </div>
        <div class="card-elevated overflow-hidden">
            @forelse($laporanTerbaru as $laporan)
            <a href="{{ route('laporan.show', $laporan) }}" wire:navigate
               class="p-4 flex items-center gap-3 {{ !$loop->last ? 'border-b border-surface-container-high' : '' }} block active:bg-surface-container-low transition-colors">
                @if($laporan->status === 'baru')
                <div class="icon-container-sm bg-tertiary-10 shrink-0">
                    <span class="material-symbols-outlined text-lg text-tertiary" style="font-variation-settings: 'FILL' 1;">error</span>
                </div>
                @elseif($laporan->status === 'proses')
                <div class="icon-container-sm bg-primary-10 shrink-0">
                    <span class="material-symbols-outlined text-lg text-primary">pending</span>
                </div>
                @else
                <div class="icon-container-sm bg-success-light shrink-0">
                    <span class="material-symbols-outlined text-lg text-success" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-on-surface truncate">{{ $laporan->aset_lokasi }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $laporan->pelapor_name }} • {{ $laporan->created_at->diffForHumans() }}</p>
                </div>
                @if($laporan->status === 'baru')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-tertiary-10 text-tertiary shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-tertiary animate-pulse"></span>
                    Baru
                </span>
                @elseif($laporan->status === 'proses')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-primary-10 text-primary shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    Proses
                </span>
                @elseif($laporan->status === 'selesai')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-success-light text-success shrink-0">
                    <span class="material-symbols-outlined text-[12px]">check</span>
                    Selesai
                </span>
                @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-danger-light text-error shrink-0">
                    Ditolak
                </span>
                @endif
            </a>
            @empty
            <div class="p-8 text-center text-on-surface-variant">
                <p class="text-sm font-medium">Belum ada laporan</p>
            </div>
            @endforelse
        </div>
    </section>

    {{-- User Profile & Logout Section --}}
    <section class="mb-6" id="user-section">
        <h3 class="text-base font-bold font-headline mb-3">Akun Saya</h3>
        <div class="card-elevated overflow-hidden">
            {{-- User Info --}}
            <div class="p-4 flex items-center gap-3 border-b border-surface-container-high">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center text-white font-bold text-base">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-on-surface">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-primary-10 text-primary mt-1">
                        {{ ucfirst($roleName) }}
                    </span>
                </div>
            </div>
            {{-- Logout --}}
            <div class="p-1">
                <livewire:auth.logout />
            </div>
        </div>
    </section>
</div>
