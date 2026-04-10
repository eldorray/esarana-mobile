<div class="animate-fade-in">
    <section class="pt-3 mb-6">
        <a href="{{ route('master-data') }}" wire:navigate class="text-primary text-sm font-semibold flex items-center gap-1 mb-3 active:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Master Data
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline">Manajemen Role</h1>
    </section>

    <button wire:click="openForm" class="btn-primary px-5 py-2.5 text-sm flex items-center gap-2 mb-5">
        <span class="material-symbols-outlined text-sm">add</span> Tambah Role
    </button>

    @if($showForm)
    <div class="card-elevated p-5 mb-5 animate-slide-up">
        <h3 class="text-base font-bold font-headline mb-4">{{ $editId ? 'Edit' : 'Tambah' }} Role</h3>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Nama Role</label>
                <input wire:model="name" type="text" class="input-precision" placeholder="Contoh: supervisor">
                @error('name') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            @if($permissions->count())
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Permissions</label>
                <div class="space-y-2 bg-surface-container-low rounded-xl p-4 max-h-48 overflow-y-auto">
                    @foreach($permissions as $perm)
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input wire:model="selectedPermissions" type="checkbox" value="{{ $perm->name }}" class="rounded-md border-outline-variant text-primary w-4 h-4">
                        <span class="text-sm text-on-surface">{{ $perm->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="flex gap-3">
                <button type="submit" class="btn-primary px-5 py-2 text-sm">Simpan</button>
                <button type="button" wire:click="$set('showForm', false)" class="text-on-surface-variant text-sm font-semibold px-5 py-2 hover:text-primary transition-colors">Batal</button>
            </div>
        </form>
    </div>
    @endif

    <div class="space-y-2.5 stagger-children">
        @forelse($roles as $role)
        <div class="card-elevated p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="icon-container bg-primary-10">
                        <span class="material-symbols-outlined text-primary text-lg">verified_user</span>
                    </div>
                    <p class="text-sm font-bold text-on-surface uppercase">{{ $role->name }}</p>
                </div>
                <div class="flex items-center gap-1">
                    <button wire:click="openForm({{ $role->id }})" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-surface-container-high transition-colors active:scale-90">
                        <span class="material-symbols-outlined text-lg text-on-surface-variant">edit</span>
                    </button>
                    <button
                        x-on:click="$dispatch('confirm-modal', {
                            title: 'Hapus Role?',
                            message: 'Role {{ $role->name }} akan dihapus. User yang memiliki role ini akan kehilangan aksesnya.',
                            icon: 'shield',
                            iconColor: 'error',
                            confirmText: 'Ya, Hapus',
                            confirmColor: 'error',
                            action: () => $wire.delete({{ $role->id }})
                        })"
                        class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-error-container transition-colors active:scale-90">
                        <span class="material-symbols-outlined text-lg text-error">delete</span>
                    </button>
                </div>
            </div>
            @if($role->permissions->count())
            <div class="flex flex-wrap gap-1.5">
                @foreach($role->permissions as $perm)
                <span class="badge badge-info">{{ $perm->name }}</span>
                @endforeach
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-12 text-on-surface-variant text-sm">Belum ada role</div>
        @endforelse
    </div>
</div>
