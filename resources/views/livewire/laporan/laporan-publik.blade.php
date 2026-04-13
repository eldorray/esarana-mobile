<div class="animate-fade-in">
    @if($submitted)
    {{-- Success State --}}
    <div class="text-center py-8">
        <div class="w-20 h-20 rounded-full bg-success-light flex items-center justify-center mx-auto mb-5">
            <span class="material-symbols-outlined text-success text-4xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        </div>
        <h2 class="text-xl font-extrabold font-headline text-on-surface">Laporan Terkirim!</h2>
        <p class="text-on-surface-variant text-sm mt-2 max-w-xs mx-auto">Terima kasih. Laporan Anda telah diterima dan akan segera ditindaklanjuti oleh tim kami.</p>

        <div class="mt-8 space-y-3">
            <button wire:click="resetForm" class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">add</span> Buat Laporan Lagi
            </button>
            <a href="{{ route('login') }}" wire:navigate class="block text-center text-primary text-sm font-semibold py-2">
                ← Kembali ke Login
            </a>
        </div>
    </div>
    @else
    {{-- Brand --}}
    <div class="text-center mb-8">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-tertiary to-tertiary-container flex items-center justify-center mx-auto mb-4 shadow-lg shadow-tertiary/20">
            <span class="material-symbols-outlined text-white text-2xl">campaign</span>
        </div>
        <h1 class="text-2xl font-extrabold font-headline text-on-surface tracking-tight">Lapor Masalah</h1>
        <p class="text-on-surface-variant text-sm mt-1">Tidak perlu login — bantu kami menjaga fasilitas.</p>
    </div>

    <form wire:submit="save" class="space-y-4">
        {{-- Nama Pelapor --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Nama Anda</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">person</span>
                <input wire:model="nama_pelapor" type="text" class="input-precision pl-11" placeholder="Nama lengkap">
            </div>
            @error('nama_pelapor') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Kontak --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">No. HP / Email</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">call</span>
                <input wire:model="kontak_pelapor" type="text" class="input-precision pl-11" placeholder="08xx atau email@domain.com">
            </div>
            @error('kontak_pelapor') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Aset / Lokasi --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Aset / Lokasi yang Dilaporkan</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">location_on</span>
                <input wire:model="aset_lokasi" type="text" class="input-precision pl-11" placeholder="Contoh: AC Ruang Meeting Lt. 2">
            </div>
            @error('aset_lokasi') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Tipe --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-3">Kategori</label>
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

        {{-- Deskripsi --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Deskripsi Detail</label>
            <textarea wire:model="deskripsi" class="input-precision" rows="4" placeholder="Jelaskan secara rinci kendala atau kebutuhan..."></textarea>
            @error('deskripsi') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Foto Bukti --}}
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Foto Bukti <span class="text-on-surface-variant font-normal normal-case">(opsional)</span></label>
            @if($foto)
                <img src="{{ $foto->temporaryUrl() }}" class="w-full h-44 object-cover rounded-2xl mb-2">
                <button type="button" wire:click="$set('foto', null)" class="text-error text-xs font-semibold flex items-center gap-1 mt-1">
                    <span class="material-symbols-outlined text-sm">delete</span> Hapus Foto
                </button>
            @else
                <div class="w-full h-28 border-2 border-dashed border-surface-container-highest rounded-2xl flex items-center justify-center mb-2">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant opacity-30">add_photo_alternate</span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <label class="cursor-pointer flex items-center justify-center gap-2 bg-primary text-white text-sm font-semibold rounded-xl px-4 py-2.5 active:scale-95 transition-transform">
                        <span class="material-symbols-outlined text-lg">photo_camera</span>
                        Kamera
                        <input wire:model="foto" type="file" accept="image/*" capture="environment" class="hidden">
                    </label>
                    <label class="cursor-pointer flex items-center justify-center gap-2 bg-surface-container-high text-on-surface text-sm font-semibold rounded-xl px-4 py-2.5 active:scale-95 transition-transform">
                        <span class="material-symbols-outlined text-lg">photo_library</span>
                        Galeri
                        <input wire:model="foto" type="file" accept="image/*" class="hidden">
                    </label>
                </div>
            @endif
            @error('foto') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Submit --}}
        <div class="pt-2">
            <button type="submit" class="btn-primary w-full py-3.5 text-sm flex items-center justify-center gap-2" wire:loading.attr="disabled">
                <span wire:loading.remove>
                    <span class="material-symbols-outlined text-sm">send</span>
                </span>
                <span wire:loading>
                    <span class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                </span>
                <span wire:loading.remove>Kirim Laporan</span>
                <span wire:loading>Mengirim...</span>
            </button>
            <p class="text-center text-[11px] text-on-surface-variant mt-3">Laporan akan segera diproses oleh tim manajemen.</p>
        </div>
    </form>

    {{-- Back to Login --}}
    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" wire:navigate class="text-primary text-sm font-semibold inline-flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Login
        </a>
    </div>
    @endif
</div>
