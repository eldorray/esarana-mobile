<div class="animate-fade-in">
    <section class="pt-3 mb-6">
        <a href="{{ route('peminjaman.index') }}" wire:navigate class="text-primary text-sm font-semibold flex items-center gap-1 mb-3 active:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline">Ajukan Peminjaman</h1>
        <p class="text-on-surface-variant text-sm mt-0.5">Pilih aset dan tentukan jadwal peminjaman.</p>
    </section>

    {{-- Flash Messages --}}
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-danger-light border border-error/20 text-error px-4 py-3 rounded-2xl">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">error</span>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    <form wire:submit="save" class="space-y-4">
        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Pilih Aset</label>
            <select wire:model="inventaris_id" class="input-precision">
                <option value="">Pilih aset yang tersedia</option>
                @foreach($inventarisTersedia as $item)
                <option value="{{ $item->id }}">{{ $item->nama }} ({{ $item->kode }})</option>
                @endforeach
            </select>
            @error('inventaris_id') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Tanggal Pinjam</label>
                <input wire:model="tanggal_pinjam" type="date" class="input-precision">
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Rencana Kembali</label>
                <input wire:model="tanggal_kembali_rencana" type="date" class="input-precision">
            </div>
        </div>

        <div>
            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Catatan</label>
            <textarea wire:model="catatan" class="input-precision" rows="3" placeholder="Alasan peminjaman..."></textarea>
        </div>

        <div class="pt-3 space-y-2">
            <button type="submit"
                    wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed"
                    class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2">
                <span wire:loading wire:target="save" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="save" class="material-symbols-outlined text-sm">send</span>
                Ajukan Peminjaman
            </button>
            <a href="{{ route('peminjaman.index') }}" wire:navigate class="block text-center text-on-surface-variant text-sm font-semibold py-3 hover:text-primary transition-colors">Batal</a>
        </div>
    </form>
</div>
