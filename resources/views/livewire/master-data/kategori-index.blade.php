<div class="animate-fade-in">
    <section class="pt-3 mb-6">
        <a href="{{ route('master-data') }}" wire:navigate class="text-primary text-sm font-semibold flex items-center gap-1 mb-3 active:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Master Data
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline">Kategori Aset</h1>
    </section>

    <button wire:click="openForm" class="btn-primary px-5 py-2.5 text-sm flex items-center gap-2 mb-5">
        <span class="material-symbols-outlined text-sm">add</span> Tambah Kategori
    </button>

    @if($showForm)
    <div class="card-elevated p-5 mb-5 animate-slide-up">
        <h3 class="text-base font-bold font-headline mb-4">{{ $editId ? 'Edit' : 'Tambah' }} Kategori</h3>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Nama Kategori</label>
                <input wire:model="nama" type="text" class="input-precision" placeholder="Contoh: Elektronik">
                @error('nama') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Deskripsi</label>
                <textarea wire:model="deskripsi" class="input-precision" rows="2" placeholder="Deskripsi singkat..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary px-5 py-2 text-sm">Simpan</button>
                <button type="button" wire:click="$set('showForm', false)" class="text-on-surface-variant text-sm font-semibold px-5 py-2 hover:text-primary transition-colors">Batal</button>
            </div>
        </form>
    </div>
    @endif

    <div class="space-y-2.5 stagger-children">
        @forelse($kategoris as $kat)
        <div class="card-elevated p-4 flex items-center justify-between">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="icon-container bg-primary-10">
                    <span class="material-symbols-outlined text-primary text-lg">{{ $kat->icon ?? 'category' }}</span>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-on-surface truncate">{{ $kat->nama }}</p>
                    <p class="text-[11px] text-on-surface-variant">{{ $kat->inventaris_count }} aset</p>
                </div>
            </div>
            <div class="flex items-center gap-1 shrink-0">
                <button wire:click="toggleActive({{ $kat->id }})" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-surface-container-high transition-colors active:scale-90">
                    <span class="material-symbols-outlined text-xl {{ $kat->is_active ? 'text-success' : 'text-on-surface-variant' }}" style="font-variation-settings: 'FILL' 1;">
                        {{ $kat->is_active ? 'toggle_on' : 'toggle_off' }}
                    </span>
                </button>
                <button wire:click="openForm({{ $kat->id }})" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-surface-container-high transition-colors active:scale-90">
                    <span class="material-symbols-outlined text-lg text-on-surface-variant">edit</span>
                </button>
                <button
                    x-on:click="$dispatch('confirm-modal', {
                        title: 'Hapus Kategori?',
                        message: 'Kategori {{ $kat->nama }} akan dihapus. Aset yang menggunakan kategori ini tidak akan terpengaruh.',
                        icon: 'category',
                        iconColor: 'error',
                        confirmText: 'Ya, Hapus',
                        confirmColor: 'error',
                        action: () => $wire.delete({{ $kat->id }})
                    })"
                    class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-error-container transition-colors active:scale-90">
                    <span class="material-symbols-outlined text-lg text-error">delete</span>
                </button>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-on-surface-variant text-sm">Belum ada kategori</div>
        @endforelse
    </div>
</div>
