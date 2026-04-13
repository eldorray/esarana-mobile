<div class="animate-fade-in">

    {{-- Header --}}
    <section class="pt-3 mb-5">
        <div class="flex items-center gap-3 mb-1">
            <a href="{{ route('master-data') }}" wire:navigate
               class="icon-container-sm bg-surface-container-high active:scale-90 transition-transform">
                <span class="material-symbols-outlined text-on-surface-variant text-lg">arrow_back</span>
            </a>
            <div>
                <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-widest">Master Data</p>
                <h1 class="text-xl font-extrabold tracking-tight text-on-surface font-headline">Backup & Restore</h1>
            </div>
        </div>
        <p class="text-on-surface-variant text-sm mt-1 ml-[2.75rem]">Kelola cadangan data dan reset operasional sistem.</p>
    </section>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mb-4 flex items-start gap-3 bg-success-light text-success px-4 py-3 rounded-2xl">
        <span class="material-symbols-outlined text-xl shrink-0" style="font-variation-settings:'FILL' 1">check_circle</span>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 flex items-start gap-3 bg-danger-light text-error px-4 py-3 rounded-2xl">
        <span class="material-symbols-outlined text-xl shrink-0" style="font-variation-settings:'FILL' 1">error</span>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    {{-- ===== BACKUP ===== --}}
    <section class="mb-4">
        <h2 class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-3">Backup Database</h2>
        <div class="card-elevated p-4 space-y-3">
            <div class="flex items-start gap-3">
                <div class="icon-container bg-primary-10 shrink-0">
                    <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings:'FILL' 1">database</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-on-surface">Download Backup</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">Unduh seluruh database sebagai file <span class="font-mono">.sqlite</span> untuk disimpan secara manual.</p>
                </div>
            </div>
            <button wire:click="downloadBackup"
                    wire:loading.attr="disabled"
                    class="w-full btn-primary py-2.5 text-sm flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="downloadBackup" class="material-symbols-outlined text-lg">download</span>
                <span wire:loading wire:target="downloadBackup" class="material-symbols-outlined text-lg animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="downloadBackup">Download Backup Sekarang</span>
                <span wire:loading wire:target="downloadBackup">Menyiapkan...</span>
            </button>
        </div>
    </section>

    {{-- ===== RESTORE ===== --}}
    <section class="mb-4">
        <h2 class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-3">Restore dari Backup</h2>
        <div class="card-elevated p-4 space-y-3">
            <div class="flex items-start gap-3">
                <div class="icon-container bg-secondary-10 shrink-0">
                    <span class="material-symbols-outlined text-secondary text-xl" style="font-variation-settings:'FILL' 1">restore</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-on-surface">Upload File Backup</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">Pilih file <span class="font-mono">.sqlite</span> backup yang valid. Sistem akan otomatis menyimpan cadangan sebelum restore.</p>
                </div>
            </div>

            @if($backupFile)
            <div class="flex items-center gap-2 bg-secondary-10 px-3 py-2 rounded-xl">
                <span class="material-symbols-outlined text-secondary text-lg">description</span>
                <span class="text-xs font-semibold text-secondary flex-1 truncate">{{ $backupFile->getClientOriginalName() }}</span>
                <button type="button" wire:click="$set('backupFile', null)" class="text-error">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>
            @else
            <label class="cursor-pointer flex items-center justify-center gap-2 border-2 border-dashed border-surface-container-highest rounded-xl px-4 py-3 hover:border-secondary transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant text-xl">upload_file</span>
                <span class="text-sm text-on-surface-variant">Tap untuk pilih file .sqlite</span>
                <input wire:model="backupFile" type="file" accept=".sqlite,application/octet-stream" class="hidden">
            </label>
            @endif
            @error('backupFile') <p class="text-error text-xs">{{ $message }}</p> @enderror

            <button wire:click="restoreBackup"
                    wire:loading.attr="disabled"
                    wire:confirm="PERHATIAN: Semua data saat ini akan digantikan oleh data dari backup. Lanjutkan?"
                    class="w-full py-2.5 text-sm font-bold rounded-xl bg-secondary-10 text-secondary hover:bg-secondary/20 flex items-center justify-center gap-2 transition-all active:scale-[0.98] disabled:opacity-50">
                <span wire:loading.remove wire:target="restoreBackup" class="material-symbols-outlined text-lg">restore</span>
                <span wire:loading wire:target="restoreBackup" class="material-symbols-outlined text-lg animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="restoreBackup">Restore Database</span>
                <span wire:loading wire:target="restoreBackup">Memproses...</span>
            </button>
        </div>
    </section>

    {{-- ===== RIWAYAT BACKUP ===== --}}
    @if($backups->isNotEmpty())
    <section class="mb-4">
        <h2 class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-3">Riwayat Backup Otomatis</h2>
        <div class="card-elevated overflow-hidden divide-y divide-surface-container-high">
            @foreach($backups as $b)
            <div class="flex items-center gap-3 px-4 py-3">
                <span class="material-symbols-outlined text-on-surface-variant text-lg shrink-0">history</span>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-on-surface truncate">{{ $b['name'] }}</p>
                    <p class="text-[10px] text-on-surface-variant">{{ $b['time'] }} &bull; {{ $b['size'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <p class="text-[10px] text-on-surface-variant mt-2 px-1">Tersimpan di <span class="font-mono">storage/app/backups/</span> — maksimal 10 entri ditampilkan.</p>
    </section>
    @endif

    {{-- ===== RESET DATA ===== --}}
    <section class="mb-6">
        <h2 class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-3">Reset Data Operasional</h2>
        <div class="rounded-2xl border-2 border-error/30 bg-danger-light/40 p-4 space-y-3"
             x-data="{ open: false }">
            <button type="button" @click="open = !open"
                    class="w-full flex items-start gap-3 text-left">
                <div class="icon-container bg-danger-light shrink-0">
                    <span class="material-symbols-outlined text-error text-xl" style="font-variation-settings:'FILL' 1">warning</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-error">Reset Data Operasional</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">Hapus inventaris, bahan habis pakai, peminjaman, laporan, lokasi & ruangan. Kategori, user, dan role <strong>tidak</strong> dihapus.</p>
                </div>
                <span class="material-symbols-outlined text-error text-lg shrink-0 transition-transform duration-200"
                      :class="open ? 'rotate-180' : ''">expand_more</span>
            </button>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-cloak
                 class="space-y-3 border-t border-error/20 pt-3">

                {{-- Tabel yang akan direset --}}
                <div class="bg-surface rounded-xl px-3 py-2.5 space-y-1.5">
                    <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2">Tabel yang akan dihapus</p>
                    @foreach(['inventaris','bahan_habis_pakais','peminjamans','laporans','lokasis','ruangans','audit_logs'] as $tbl)
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-error text-[14px]" style="font-variation-settings:'FILL' 1">delete</span>
                        <span class="font-mono text-xs text-on-surface">{{ $tbl }}</span>
                    </div>
                    @endforeach
                    <div class="border-t border-surface-container-high mt-2 pt-2 space-y-1.5">
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Tabel yang AMAN (tidak dihapus)</p>
                        @foreach(['kategoris','users','roles','permissions'] as $tbl)
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-success text-[14px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                            <span class="font-mono text-xs text-on-surface">{{ $tbl }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-error block mb-1.5">Ketik <span class="font-mono bg-error/10 px-1.5 py-0.5 rounded">RESET</span> untuk konfirmasi</label>
                    <input wire:model="resetKonfirmasi"
                           type="text"
                           placeholder="Ketik RESET di sini..."
                           class="input-precision text-sm font-mono border-error/50 focus:border-error">
                    @error('resetKonfirmasi') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button wire:click="resetData"
                        wire:loading.attr="disabled"
                        class="w-full py-2.5 text-sm font-bold rounded-xl bg-error text-white hover:bg-error/90 flex items-center justify-center gap-2 transition-all active:scale-[0.98] disabled:opacity-50">
                    <span wire:loading.remove wire:target="resetData" class="material-symbols-outlined text-lg">delete_forever</span>
                    <span wire:loading wire:target="resetData" class="material-symbols-outlined text-lg animate-spin">progress_activity</span>
                    <span wire:loading.remove wire:target="resetData">Jalankan Reset Data</span>
                    <span wire:loading wire:target="resetData">Mereset...</span>
                </button>
                <p class="text-[10px] text-on-surface-variant text-center">Backup otomatis akan dibuat sebelum reset dijalankan.</p>
            </div>
        </div>
    </section>

</div>
