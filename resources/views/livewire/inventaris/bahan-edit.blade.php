<div class="animate-fade-in">
    <section class="pt-3 mb-6">
        <a href="{{ route('inventaris.bahan.show', $bahan) }}" wire:navigate class="text-primary text-sm font-semibold flex items-center gap-1 mb-3 active:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Detail Bahan
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline">Edit Bahan</h1>
        <p class="text-on-surface-variant text-sm mt-0.5 truncate">{{ $bahan->nama }}</p>
    </section>

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-danger-light border border-error/20 text-error px-4 py-3 rounded-2xl">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">error</span>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    <form wire:submit="save" class="space-y-4">
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Nama Bahan <span class="text-error">*</span></label>
            <input wire:model="nama" type="text" class="input-precision" placeholder="Nama bahan">
            @error('nama') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Kode Item <span class="text-error">*</span></label>
            <input wire:model="kode" type="text" class="input-precision" placeholder="BHP-ATK-001">
            @error('kode') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Kategori</label>
            <select wire:model="kategori_id" class="input-precision">
                <option value="">Pilih Kategori</option>
                @foreach($kategoris as $kat)
                <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                @endforeach
            </select>
            @error('kategori_id') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Lokasi Penyimpanan</label>
            <select wire:model="ruangan_id" class="input-precision">
                <option value="">Pilih Ruangan</option>
                @foreach($ruangans as $ruangan)
                <option value="{{ $ruangan->id }}">{{ $ruangan->lokasi?->nama }} — {{ $ruangan->nama }}</option>
                @endforeach
            </select>
            @error('ruangan_id') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Stok Saat Ini <span class="text-error">*</span></label>
                <input wire:model="stok" type="number" min="0" class="input-precision">
                @error('stok') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Satuan <span class="text-error">*</span></label>
                <input wire:model="satuan" type="text" class="input-precision" placeholder="pcs / rim / botol">
                @error('satuan') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Stok Minimum <span class="text-error">*</span></label>
                <input wire:model="stok_minimum" type="number" min="0" class="input-precision">
                @error('stok_minimum') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Harga Satuan (Rp)</label>
                <input wire:model="harga_satuan" type="number" min="0" step="100" class="input-precision">
                @error('harga_satuan') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="pt-3">
            <button type="submit"
                    wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed"
                    class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2">
                <span wire:loading wire:target="save" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="save" class="material-symbols-outlined text-sm">check</span>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
