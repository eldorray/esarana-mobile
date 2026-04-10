<div class="animate-fade-in">
    <section class="pt-3 mb-6">
        <a href="{{ route('lokasi.index') }}" wire:navigate class="text-primary text-sm font-semibold flex items-center gap-1 mb-3 active:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
        </a>

        @if($editingLokasi)
        {{-- Editing Lokasi --}}
        <div class="card-elevated p-5 animate-slide-up">
            <h2 class="text-lg font-bold font-headline mb-4">Edit Lokasi</h2>
            <form wire:submit="updateLokasi" class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Nama Lokasi</label>
                    <input wire:model="nama" type="text" class="input-precision">
                    @error('nama') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Alamat</label>
                    <textarea wire:model="alamat" class="input-precision" rows="2"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Tipe</label>
                        <select wire:model="tipe" class="input-precision">
                            <option value="utama">Utama</option>
                            <option value="satelit">Satelit</option>
                            <option value="gudang">Gudang</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Status</label>
                        <select wire:model="status" class="input-precision">
                            <option value="operasional">Operasional</option>
                            <option value="renovasi">Renovasi</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary px-5 py-2.5 text-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">check</span> Simpan
                    </button>
                    <button type="button" wire:click="toggleEditLokasi" class="text-on-surface-variant text-sm font-semibold px-5 py-2.5">Batal</button>
                </div>
            </form>
        </div>
        @else
        {{-- Display Lokasi --}}
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline">{{ $lokasi->nama }}</h1>
                <p class="text-on-surface-variant text-sm mt-0.5">{{ $lokasi->alamat ?? 'Alamat belum diisi' }}</p>
            </div>
            @if($lokasi->status === 'operasional')
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-success-light text-success mt-1">
                <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                Operasional
            </span>
            @elseif($lokasi->status === 'renovasi')
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-warning-light text-warning-amber mt-1">Renovasi</span>
            @else
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-danger-light text-error mt-1">Nonaktif</span>
            @endif
        </div>

        {{-- Admin Actions --}}
        @role('administrator')
        <div class="flex gap-2 mt-4">
            <button wire:click="toggleEditLokasi" class="flex-1 flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-primary-10 text-primary text-sm font-bold active:scale-95 transition-transform">
                <span class="material-symbols-outlined text-[18px]">edit</span> Edit
            </button>
            <button
                x-on:click="$dispatch('confirm-modal', {
                    title: 'Hapus Lokasi?',
                    message: 'Lokasi beserta semua ruangan di dalamnya akan dihapus secara permanen.',
                    icon: 'delete_forever',
                    iconColor: 'error',
                    confirmText: 'Hapus Permanen',
                    confirmColor: 'error',
                    action: () => $wire.deleteLokasi()
                })"
                class="flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-danger-light text-error text-sm font-bold active:scale-95 transition-transform">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>
        </div>
        @endrole
        @endif
    </section>

    {{-- Info Cards --}}
    <div class="grid grid-cols-2 gap-3 mb-6">
        <div class="card-elevated p-4 text-center">
            <div class="icon-container bg-primary-10 mx-auto mb-2">
                <span class="material-symbols-outlined text-primary text-xl">meeting_room</span>
            </div>
            <div class="text-2xl font-extrabold text-on-surface font-headline">{{ $lokasi->ruangans->count() }}</div>
            <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider mt-0.5">Ruangan</p>
        </div>
        <div class="card-elevated p-4 text-center">
            <div class="icon-container bg-secondary-10 mx-auto mb-2">
                <span class="material-symbols-outlined text-secondary text-xl" style="font-variation-settings: 'FILL' 1;">location_on</span>
            </div>
            <div class="text-sm font-bold text-on-surface uppercase">{{ $lokasi->tipe }}</div>
            <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider mt-0.5">Tipe Lokasi</p>
        </div>
    </div>

    {{-- Daftar Ruangan --}}
    <section class="mb-6">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-base font-bold font-headline">Daftar Ruangan</h3>
            @role('administrator')
            <button wire:click="openRuanganForm" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-primary-10 text-primary text-xs font-bold active:scale-95 transition-transform">
                <span class="material-symbols-outlined text-[16px]">add</span> Tambah
            </button>
            @endrole
        </div>

        {{-- Ruangan Form --}}
        @if($showRuanganForm)
        <div class="card-elevated p-5 mb-4 animate-slide-up">
            <h4 class="text-sm font-bold font-headline mb-3">{{ $editRuanganId ? 'Edit' : 'Tambah' }} Ruangan</h4>
            <form wire:submit="saveRuangan" class="space-y-3">
                <div>
                    <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-1.5">Nama Ruangan</label>
                    <input wire:model="ruanganNama" type="text" class="input-precision" placeholder="Contoh: Ruang Meeting Lt. 2">
                    @error('ruanganNama') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-1.5">Lantai</label>
                        <input wire:model="ruanganLantai" type="text" class="input-precision" placeholder="1">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-1.5">Kapasitas</label>
                        <input wire:model="ruanganKapasitas" type="number" class="input-precision" placeholder="20">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-1.5">Status</label>
                    <select wire:model="ruanganStatus" class="input-precision">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-1">
                    <button type="submit" class="btn-primary px-5 py-2 text-sm">Simpan</button>
                    <button type="button" wire:click="$set('showRuanganForm', false)" class="text-on-surface-variant text-sm font-semibold px-5 py-2">Batal</button>
                </div>
            </form>
        </div>
        @endif

        {{-- Ruangan List --}}
        <div class="space-y-2.5 stagger-children">
            @forelse($lokasi->ruangans as $ruangan)
            <div class="card-elevated p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="icon-container bg-primary-10">
                            <span class="material-symbols-outlined text-primary text-xl">meeting_room</span>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-on-surface text-sm truncate">{{ $ruangan->nama }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">
                                Lantai {{ $ruangan->lantai ?? '-' }} • Kapasitas {{ $ruangan->kapasitas ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        @if($ruangan->status === 'aktif')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-success-light text-success">Aktif</span>
                        @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-warning-light text-warning-amber">Nonaktif</span>
                        @endif

                        @role('administrator')
                        <button wire:click="openRuanganForm({{ $ruangan->id }})" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-surface-container-high transition-colors active:scale-90">
                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">edit</span>
                        </button>
                        <button
                            x-on:click="$dispatch('confirm-modal', {
                                title: 'Hapus Ruangan?',
                                message: 'Ruangan {{ $ruangan->nama }} akan dihapus secara permanen.',
                                icon: 'meeting_room',
                                iconColor: 'error',
                                confirmText: 'Ya, Hapus',
                                confirmColor: 'error',
                                action: () => $wire.deleteRuangan({{ $ruangan->id }})
                            })"
                            class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-error-container transition-colors active:scale-90">
                            <span class="material-symbols-outlined text-[18px] text-error">delete</span>
                        </button>
                        @endrole
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-on-surface-variant">
                <div class="icon-container-lg bg-surface-container-high mx-auto mb-3">
                    <span class="material-symbols-outlined text-2xl opacity-40">door_open</span>
                </div>
                <p class="text-sm font-medium">Belum ada ruangan</p>
                @role('administrator')
                <button wire:click="openRuanganForm" class="mt-3 btn-primary px-5 py-2 text-sm inline-flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">add</span> Tambah Ruangan
                </button>
                @endrole
            </div>
            @endforelse
        </div>
    </section>
</div>
