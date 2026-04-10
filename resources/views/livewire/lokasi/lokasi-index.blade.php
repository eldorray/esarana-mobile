<div class="animate-fade-in">
    <section class="pt-3 mb-6">
        <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-widest">Infrastruktur</p>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline mt-1">Lokasi & Ruangan</h1>
    </section>

    {{-- Stats Hero --}}
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="col-span-2 hero-gradient rounded-2xl p-5 text-white relative">
            <div class="relative z-10">
                <p class="text-white/70 text-xs font-semibold uppercase tracking-widest">Total Infrastruktur</p>
                <div class="text-4xl font-extrabold font-headline mt-1">{{ $totalInfrastruktur }}</div>
                <p class="text-white/60 text-xs mt-1">Titik penyimpanan aktif</p>
                <a href="{{ route('lokasi.create') }}" wire:navigate class="mt-4 bg-white text-primary px-4 py-2 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 shadow-sm active:scale-95 transition-transform">
                    <span class="material-symbols-outlined text-sm">add</span> Tambah
                </a>
            </div>
        </div>
        <div class="card-elevated p-4 flex flex-col items-center justify-center text-center">
            <div class="icon-container bg-primary-10 mb-2">
                <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings: 'FILL' 1;">location_on</span>
            </div>
            <div class="text-xl font-bold text-on-surface">{{ $gedungUtama }}</div>
            <div class="text-[9px] font-medium text-on-surface-variant uppercase tracking-wider mt-0.5">Gedung Utama</div>
        </div>
    </div>

    {{-- Search --}}
    <div class="mb-5">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari lokasi..." class="input-precision pl-11">
        </div>
    </div>

    {{-- Location Cards --}}
    <div class="space-y-3 stagger-children">
        @forelse($lokasis as $lokasi)
        <a href="{{ route('lokasi.show', $lokasi) }}" wire:navigate class="block group">
            <div class="card-elevated overflow-hidden">
                <div class="p-5">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="icon-container bg-primary-10">
                                <span class="material-symbols-outlined text-primary text-xl">
                                    {{ $lokasi->tipe === 'gudang' ? 'warehouse' : 'location_on' }}
                                </span>
                            </div>
                            @if($lokasi->status === 'operasional')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-success-light text-success">
                                <span class="w-1.5 h-1.5 rounded-full bg-success"></span> Operasional
                            </span>
                            @elseif($lokasi->status === 'renovasi')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-warning-light text-warning-amber">Renovasi</span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-danger-light text-error">Nonaktif</span>
                            @endif
                        </div>
                        <span class="badge badge-info">{{ $lokasi->ruangans_count }} RUANGAN</span>
                    </div>
                    <h3 class="text-base font-bold text-on-surface">{{ $lokasi->nama }}</h3>
                    <p class="text-sm text-on-surface-variant mt-0.5">{{ $lokasi->alamat ?? 'Alamat belum diisi' }}</p>

                    @if($lokasi->ruangans->count() > 0)
                    <div class="space-y-1.5 mt-4">
                        @foreach($lokasi->ruangans->take(2) as $ruangan)
                        <div class="flex items-center gap-2 p-2 bg-surface-container-low rounded-lg {{ $loop->first ? 'border-l-2 border-primary' : '' }}">
                            <span class="material-symbols-outlined {{ $loop->first ? 'text-primary' : 'text-on-surface-variant' }} text-lg">meeting_room</span>
                            <span class="text-sm font-medium text-on-surface">{{ $ruangan->nama }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="mt-4 flex justify-between items-center pt-3 border-t border-surface-container-high">
                        <span class="text-[11px] text-on-surface-variant">{{ $lokasi->updated_at->diffForHumans() }}</span>
                        <div class="flex items-center gap-1">
                            @role('administrator')
                            <button
                                x-on:click.prevent="$dispatch('confirm-modal', {
                                    title: 'Hapus Lokasi?',
                                    message: 'Lokasi {{ $lokasi->nama }} beserta semua ruangan akan dihapus permanen.',
                                    icon: 'delete_forever',
                                    iconColor: 'error',
                                    confirmText: 'Hapus Permanen',
                                    confirmColor: 'error',
                                    action: () => $wire.deleteLokasi({{ $lokasi->id }})
                                })"
                                class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-error-container transition-colors active:scale-90">
                                <span class="material-symbols-outlined text-[18px] text-error">delete</span>
                            </button>
                            @endrole
                            <span class="material-symbols-outlined text-primary text-xl group-hover:translate-x-1 transition-transform duration-300">arrow_forward</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="text-center py-16 text-on-surface-variant">
            <div class="icon-container-lg bg-surface-container-high mx-auto mb-3">
                <span class="material-symbols-outlined text-2xl opacity-40">location_off</span>
            </div>
            <p class="text-sm font-medium mb-4">Belum ada lokasi terdaftar</p>
            <a href="{{ route('lokasi.create') }}" wire:navigate class="btn-primary px-6 py-2.5 text-sm inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">add</span> Tambah Lokasi
            </a>
        </div>
        @endforelse
    </div>
</div>
