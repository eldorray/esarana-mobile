<div class="animate-fade-in">
    <section class="pt-3 mb-6">
        <a href="{{ route('laporan.index') }}" wire:navigate class="text-primary text-sm font-semibold flex items-center gap-1 mb-3 active:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline">Lapor Masalah</h1>
        <p class="text-on-surface-variant text-sm mt-0.5">Bantu kami menjaga kualitas fasilitas.</p>
    </section>

    <form wire:submit="save" class="space-y-4">
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Identitas Aset / Lokasi</label>
            <input wire:model="aset_lokasi" type="text" class="input-precision" placeholder="Contoh: AC Ruang Meeting Lt. 2">
            @error('aset_lokasi') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-3">Kategori Laporan</label>
            <div class="grid grid-cols-2 gap-3">
                <button type="button" wire:click="$set('tipe', 'kerusakan')"
                    class="p-4 rounded-xl border-2 flex flex-col items-center gap-2 transition-all active:scale-95 {{ $tipe === 'kerusakan' ? 'border-primary bg-primary-5 shadow-sm' : 'border-outline-variant bg-white' }}">
                    <div class="icon-container {{ $tipe === 'kerusakan' ? 'bg-primary-10' : 'bg-surface-container-high' }}">
                        <span class="material-symbols-outlined text-xl {{ $tipe === 'kerusakan' ? 'text-primary' : 'text-on-surface-variant' }}">build</span>
                    </div>
                    <span class="text-sm font-semibold {{ $tipe === 'kerusakan' ? 'text-primary' : 'text-on-surface-variant' }}">Kerusakan</span>
                </button>
                <button type="button" wire:click="$set('tipe', 'permintaan')"
                    class="p-4 rounded-xl border-2 flex flex-col items-center gap-2 transition-all active:scale-95 {{ $tipe === 'permintaan' ? 'border-primary bg-primary-5 shadow-sm' : 'border-outline-variant bg-white' }}">
                    <div class="icon-container {{ $tipe === 'permintaan' ? 'bg-primary-10' : 'bg-surface-container-high' }}">
                        <span class="material-symbols-outlined text-xl {{ $tipe === 'permintaan' ? 'text-primary' : 'text-on-surface-variant' }}">shopping_cart</span>
                    </div>
                    <span class="text-sm font-semibold {{ $tipe === 'permintaan' ? 'text-primary' : 'text-on-surface-variant' }}">Permintaan</span>
                </button>
            </div>
        </div>

        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Deskripsi Detail</label>
            <textarea wire:model="deskripsi" class="input-precision" rows="4" placeholder="Jelaskan secara rinci kendala yang dialami..."></textarea>
            @error('deskripsi') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="pt-3">
            <button type="submit" class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">send</span> Kirim Laporan
            </button>
            <p class="text-center text-[11px] text-on-surface-variant mt-3">Laporan akan segera diproses oleh tim manajemen.</p>
        </div>
    </form>
</div>
