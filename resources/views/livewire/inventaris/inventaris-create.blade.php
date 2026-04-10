<div class="animate-fade-in">
    <section class="pt-3 mb-6">
        <a href="{{ route('inventaris.index') }}" wire:navigate class="text-primary text-sm font-semibold flex items-center gap-1 mb-3 active:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline">Input Data Baru</h1>
        <p class="text-on-surface-variant text-sm mt-0.5">Lengkapi formulir untuk memperbarui sistem inventaris.</p>
    </section>

    {{-- Flash Error --}}
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-danger-light border border-error/20 text-error px-4 py-3 rounded-2xl">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">error</span>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Step Indicator --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-xl flex items-center justify-center text-sm font-bold transition-all duration-300 {{ $step === 1 ? 'bg-primary text-white shadow-sm' : 'bg-surface-container-high text-on-surface-variant' }}">1</span>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider {{ $step === 1 ? 'text-primary' : 'text-on-surface-variant' }}">Langkah 1</span>
                <p class="text-xs font-medium {{ $step === 1 ? 'text-on-surface' : 'text-on-surface-variant' }}">Detail Barang</p>
            </div>
        </div>
        <div class="flex-1 h-0.5 rounded {{ $step >= 2 ? 'bg-primary' : 'bg-surface-container-highest' }} transition-colors duration-300"></div>
        <div class="flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-xl flex items-center justify-center text-sm font-bold transition-all duration-300 {{ $step === 2 ? 'bg-primary text-white shadow-sm' : 'bg-surface-container-high text-on-surface-variant' }}">2</span>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider {{ $step === 2 ? 'text-primary' : 'text-on-surface-variant' }}">Langkah 2</span>
                <p class="text-xs font-medium {{ $step === 2 ? 'text-on-surface' : 'text-on-surface-variant' }}">Kategori & Stok</p>
            </div>
        </div>
    </div>

    <form wire:submit="{{ $step === 2 ? 'save' : 'nextStep' }}">
        @if($step === 1)
        <div class="space-y-4 animate-slide-up">

            {{-- Foto Barang --}}
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Foto Barang <span class="text-on-surface-variant font-normal normal-case">(opsional)</span></label>
                @if($gambar)
                    <img src="{{ $gambar->temporaryUrl() }}" class="w-full h-44 object-cover rounded-2xl mb-2">
                    <button type="button" wire:click="$set('gambar', null)" class="text-error text-xs font-semibold flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">delete</span> Hapus Foto
                    </button>
                @else
                    <label class="cursor-pointer flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-surface-container-highest rounded-2xl hover:border-primary transition-colors gap-2">
                        <span class="material-symbols-outlined text-3xl text-on-surface-variant opacity-50">upload</span>
                        <span class="text-xs text-on-surface-variant">Tap untuk upload foto</span>
                        <input wire:model="gambar" type="file" accept="image/*" class="hidden">
                    </label>
                @endif
                @error('gambar') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Nama Barang</label>
                <input wire:model="nama" type="text" class="input-precision" placeholder="Contoh: MacBook Pro M2 2023">
                @error('nama') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Kode Inventaris</label>
                <input wire:model="kode" type="text" class="input-precision" placeholder="INV-PRD-2024-001">
                @error('kode') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Deskripsi Spesifikasi</label>
                <textarea wire:model="deskripsi" class="input-precision" rows="3" placeholder="Masukkan detail spesifikasi teknis..."></textarea>
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Gudang Penyimpanan</label>
                <select wire:model="ruangan_id" class="input-precision">
                    <option value="">Pilih Ruangan</option>
                    @foreach($ruangans as $ruangan)
                    <option value="{{ $ruangan->id }}">{{ $ruangan->lokasi?->nama }} - {{ $ruangan->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-3">
                <button type="submit" class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2">
                    Lanjutkan <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </div>
        </div>
        @else
        <div class="space-y-4 animate-slide-up">
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Kategori</label>
                <select wire:model="kategori_id" class="input-precision">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Serial Number</label>
                <input wire:model="serial_number" type="text" class="input-precision" placeholder="SN-XXXX-XXXX">
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Kondisi</label>
                <select wire:model="kondisi" class="input-precision">
                    <option value="baik">Baik</option>
                    <option value="cukup">Cukup</option>
                    <option value="rusak_ringan">Rusak Ringan</option>
                    <option value="rusak_berat">Rusak Berat</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Nilai Aset (Rp)</label>
                <input wire:model="nilai_aset" type="number" class="input-precision" placeholder="0">
                @error('nilai_aset') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Tanggal Perolehan</label>
                <input wire:model="tanggal_perolehan" type="date" class="input-precision">
            </div>

            <div class="pt-3 space-y-2">
                <button type="submit"
                        wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed"
                        class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2">
                    <span wire:loading wire:target="save" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                    <span wire:loading.remove wire:target="save" class="material-symbols-outlined text-sm">check</span>
                    Simpan Data
                </button>
                <button type="button" wire:click="prevStep" class="w-full text-center text-on-surface-variant text-sm font-semibold py-3 hover:text-primary transition-colors">Kembali ke Langkah 1</button>
            </div>
        </div>
        @endif
    </form>
</div>
