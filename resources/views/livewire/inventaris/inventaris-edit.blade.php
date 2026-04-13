<div class="animate-fade-in">
    <section class="pt-3 mb-6">
        <a href="{{ route('inventaris.show', $inventaris) }}" wire:navigate class="text-primary text-sm font-semibold flex items-center gap-1 mb-3 active:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline">Edit Inventaris</h1>
        <p class="text-on-surface-variant text-sm mt-0.5">Perbarui data untuk <span class="font-semibold text-on-surface">{{ $inventaris->kode }}</span></p>
    </section>

    {{-- Flash Messages --}}
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-danger-light border border-error/20 text-error px-4 py-3 rounded-2xl">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">error</span>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    <form wire:submit="save" class="space-y-5">

        {{-- Foto Barang --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Foto Barang</label>
            <div class="relative">
                @if($gambar)
                    <img src="{{ $gambar->temporaryUrl() }}" class="w-full h-48 object-cover rounded-2xl mb-3">
                @elseif($inventaris->gambar)
                    <img src="{{ Storage::url($inventaris->gambar) }}" class="w-full h-48 object-cover rounded-2xl mb-3">
                @else
                    <div class="w-full h-32 bg-surface-container-high rounded-2xl flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-4xl text-on-surface-variant opacity-40">image</span>
                    </div>
                @endif
                <div class="grid grid-cols-2 gap-2">
                    <label class="cursor-pointer flex items-center justify-center gap-2 bg-primary text-white text-sm font-semibold rounded-xl px-4 py-2.5 active:scale-95 transition-transform">
                        <span class="material-symbols-outlined text-lg">photo_camera</span>
                        Kamera
                        <input wire:model="gambar" type="file" accept="image/*" capture="environment" class="hidden">
                    </label>
                    <label class="cursor-pointer flex items-center justify-center gap-2 bg-surface-container-high text-on-surface text-sm font-semibold rounded-xl px-4 py-2.5 active:scale-95 transition-transform">
                        <span class="material-symbols-outlined text-lg">photo_library</span>
                        Galeri
                        <input wire:model="gambar" type="file" accept="image/*" class="hidden">
                    </label>
                </div>
                @if($gambar || $inventaris->gambar)
                <button type="button" wire:click="$set('gambar', null)" class="mt-2 text-error text-xs font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">delete</span> Hapus Foto
                </button>
                @endif
                @error('gambar') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Nama Barang --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Nama Barang</label>
            <input wire:model="nama" type="text" class="input-precision" placeholder="Contoh: MacBook Pro M2 2023">
            @error('nama') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        {{-- Kode Inventaris --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Kode Inventaris</label>
            <input wire:model="kode" type="text" class="input-precision" placeholder="INV-PRD-2024-001">
            @error('kode') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Deskripsi Spesifikasi</label>
            <textarea wire:model="deskripsi" class="input-precision" rows="3" placeholder="Masukkan detail spesifikasi teknis..."></textarea>
        </div>

        {{-- Kategori --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Kategori</label>
            <select wire:model="kategori_id" class="input-precision">
                <option value="">Pilih Kategori</option>
                @foreach($kategoris as $kat)
                <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                @endforeach
            </select>
        </div>

        {{-- Ruangan --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Gudang Penyimpanan</label>
            <select wire:model="ruangan_id" class="input-precision">
                <option value="">Pilih Ruangan</option>
                @foreach($ruangans as $ruangan)
                <option value="{{ $ruangan->id }}">{{ $ruangan->lokasi?->nama }} - {{ $ruangan->nama }}</option>
                @endforeach
            </select>
        </div>

        {{-- Serial Number --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Serial Number</label>
            <input wire:model="serial_number" type="text" class="input-precision" placeholder="SN-XXXX-XXXX">
            @error('serial_number') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        {{-- Kondisi --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Kondisi</label>
            <select wire:model="kondisi" class="input-precision">
                <option value="baik">Baik</option>
                <option value="cukup">Cukup</option>
                <option value="rusak_ringan">Rusak Ringan</option>
                <option value="rusak_berat">Rusak Berat</option>
            </select>
        </div>

        {{-- Status --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Status</label>
            <select wire:model="status" class="input-precision">
                <option value="tersedia">Tersedia</option>
                <option value="dipinjam">Dipinjam</option>
                <option value="maintenance">Maintenance</option>
                <option value="rusak">Rusak</option>
            </select>
        </div>

        {{-- Nilai Aset --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Nilai Aset (Rp)</label>
            <input wire:model="nilai_aset" type="number" class="input-precision" placeholder="0">
            @error('nilai_aset') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        {{-- Tanggal Perolehan --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Tanggal Perolehan</label>
            <input wire:model="tanggal_perolehan" type="date" class="input-precision">
        </div>

        {{-- Actions --}}
        <div class="pt-3 space-y-2">
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed"
                    class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2">
                <span wire:loading wire:target="save" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="save" class="material-symbols-outlined text-sm">save</span>
                Simpan Perubahan
            </button>
            <a href="{{ route('inventaris.show', $inventaris) }}" wire:navigate
               class="block text-center text-on-surface-variant text-sm font-semibold py-3 hover:text-primary transition-colors">
               Batal
            </a>
        </div>
    </form>
</div>
