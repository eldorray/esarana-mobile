<div class="animate-fade-in">
    <section class="pt-3 mb-6">
        <a href="{{ route('lokasi.index') }}" wire:navigate class="text-primary text-sm font-semibold flex items-center gap-1 mb-3 active:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline">Tambah Lokasi</h1>
        <p class="text-on-surface-variant text-sm mt-0.5">Tambahkan lokasi baru ke infrastruktur.</p>
    </section>

    <form wire:submit="save" class="space-y-4">
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Nama Lokasi</label>
            <input wire:model="nama" type="text" class="input-precision" placeholder="Contoh: Gudang Utama Jakarta">
            @error('nama') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Alamat</label>
            <textarea wire:model="alamat" class="input-precision" rows="3" placeholder="Masukkan alamat lengkap..."></textarea>
        </div>

        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Tipe Lokasi</label>
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
                <option value="renovasi">Dalam Renovasi</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>

        <div class="pt-3 space-y-2">
            <button type="submit" class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2">
                Simpan Lokasi <span class="material-symbols-outlined text-sm">check</span>
            </button>
            <a href="{{ route('lokasi.index') }}" wire:navigate class="block text-center text-on-surface-variant text-sm font-semibold py-3 hover:text-primary transition-colors">Batal</a>
        </div>
    </form>
</div>
