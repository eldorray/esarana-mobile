<div class="animate-fade-in">
    <section class="pt-3 mb-6">
        <a href="{{ route('master-data') }}" wire:navigate class="text-primary text-sm font-semibold flex items-center gap-1 mb-3 active:opacity-70 transition-opacity">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Master Data
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-on-surface font-headline">Manajemen User</h1>
    </section>

    <button wire:click="openForm" class="btn-primary px-5 py-2.5 text-sm flex items-center gap-2 mb-5">
        <span class="material-symbols-outlined text-sm">person_add</span> Tambah User
    </button>

    @if($showForm)
    <div class="card-elevated p-5 mb-5 animate-slide-up">
        <h3 class="text-base font-bold font-headline mb-4">{{ $editId ? 'Edit' : 'Tambah' }} User</h3>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Nama</label>
                <input wire:model="name" type="text" class="input-precision" placeholder="Nama lengkap">
                @error('name') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Email</label>
                <input wire:model="email" type="email" class="input-precision" placeholder="email@domain.com">
                @error('email') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Password {{ $editId ? '(Kosongkan jika tidak diubah)' : '' }}</label>
                <input wire:model="password" type="password" class="input-precision" placeholder="Min 8 karakter">
                @error('password') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider block mb-2">Role</label>
                <select wire:model="role" class="input-precision">
                    <option value="">Pilih Role</option>
                    @foreach($roles as $r)
                    <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                    @endforeach
                </select>
                @error('role') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary px-5 py-2 text-sm">Simpan</button>
                <button type="button" wire:click="$set('showForm', false)" class="text-on-surface-variant text-sm font-semibold px-5 py-2 hover:text-primary transition-colors">Batal</button>
            </div>
        </form>
    </div>
    @endif

    <div class="space-y-2.5 stagger-children">
        @forelse($users as $user)
        <div class="card-elevated p-4 flex items-center justify-between">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-on-surface truncate">{{ $user->name }}</p>
                    <p class="text-[11px] text-on-surface-variant truncate">{{ $user->email }}</p>
                    @if($user->roles->count())
                    <span class="badge badge-info mt-1">{{ strtoupper($user->roles->first()->name) }}</span>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-1 shrink-0">
                <button wire:click="openForm({{ $user->id }})" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-surface-container-high transition-colors active:scale-90">
                    <span class="material-symbols-outlined text-lg text-on-surface-variant">edit</span>
                </button>
                <button
                    x-on:click="$dispatch('confirm-modal', {
                        title: 'Hapus User?',
                        message: 'Akun {{ $user->name }} akan dihapus permanen dan tidak dapat dikembalikan.',
                        icon: 'person_remove',
                        iconColor: 'error',
                        confirmText: 'Ya, Hapus',
                        confirmColor: 'error',
                        action: () => $wire.delete({{ $user->id }})
                    })"
                    class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-error-container transition-colors active:scale-90">
                    <span class="material-symbols-outlined text-lg text-error">delete</span>
                </button>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-on-surface-variant text-sm">Belum ada user</div>
        @endforelse
    </div>
</div>
