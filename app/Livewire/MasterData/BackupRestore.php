<?php

namespace App\Livewire\MasterData;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class BackupRestore extends Component
{
    use WithFileUploads;

    public $backupFile = null;
    public bool $confirmReset = false;
    public string $resetKonfirmasi = '';

    // Tabel yang di-reset (kecuali kategori, users, roles)
    const RESET_TABLES = [
        'inventaris',
        'bahan_habis_pakais',
        'peminjamans',
        'laporans',
        'lokasis',
        'ruangans',
        'audit_logs',
    ];

    public function downloadBackup()
    {
        $dbPath = database_path('database.sqlite');

        if (! file_exists($dbPath)) {
            session()->flash('error', 'File database tidak ditemukan.');
            return;
        }

        $filename = 'backup_esarpras_' . now()->format('Ymd_His') . '.sqlite';
        $backupPath = storage_path('app/backups/' . $filename);

        if (! is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        copy($dbPath, $backupPath);

        return response()->download($backupPath, $filename, [
            'Content-Type' => 'application/octet-stream',
        ])->deleteFileAfterSend(true);
    }

    public function restoreBackup()
    {
        $this->validate([
            'backupFile' => 'required|file|max:51200',
        ], [
            'backupFile.required' => 'Pilih file backup terlebih dahulu.',
            'backupFile.max'      => 'Ukuran file maksimal 50MB.',
        ]);

        $tmpPath = $this->backupFile->getRealPath();

        // Verifikasi file adalah SQLite yang valid
        $header = file_get_contents($tmpPath, false, null, 0, 16);
        if (strpos($header, 'SQLite format 3') !== 0) {
            session()->flash('error', 'File bukan backup SQLite yang valid.');
            return;
        }

        $dbPath = database_path('database.sqlite');

        // Buat backup otomatis sebelum restore
        $autoBackup = storage_path('app/backups/pre_restore_' . now()->format('Ymd_His') . '.sqlite');
        if (! is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }
        copy($dbPath, $autoBackup);

        // Tutup koneksi DB, replace file, reconnect
        DB::disconnect();
        copy($tmpPath, $dbPath);
        DB::reconnect();

        $this->reset('backupFile');
        session()->flash('success', 'Database berhasil di-restore dari backup.');
    }

    public function resetData()
    {
        if ($this->resetKonfirmasi !== 'RESET') {
            session()->flash('error', 'Ketik RESET untuk konfirmasi.');
            return;
        }

        $dbPath = database_path('database.sqlite');

        // Backup otomatis sebelum reset
        $autoBackup = storage_path('app/backups/pre_reset_' . now()->format('Ymd_His') . '.sqlite');
        if (! is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }
        copy($dbPath, $autoBackup);

        // Hapus file storage (foto inventaris, laporan, dll)
        foreach (['inventaris', 'laporan', 'bahan'] as $folder) {
            if (Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->deleteDirectory($folder);
            }
        }

        // Truncate tabel satu per satu dengan urutan yang aman (FK)
        DB::statement('PRAGMA foreign_keys = OFF');
        foreach (self::RESET_TABLES as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('PRAGMA foreign_keys = ON');

        $this->reset('confirmReset', 'resetKonfirmasi');
        session()->flash('success', 'Data operasional berhasil direset. Kategori, user, dan role tetap terjaga.');
    }

    public function render()
    {
        $backups = collect();
        $backupDir = storage_path('app/backups');
        if (is_dir($backupDir)) {
            $files = glob($backupDir . '/*.sqlite');
            rsort($files);
            $backups = collect(array_slice($files, 0, 10))->map(fn($f) => [
                'name' => basename($f),
                'size' => round(filesize($f) / 1024, 1) . ' KB',
                'time' => date('d M Y H:i', filemtime($f)),
            ]);
        }

        return view('livewire.master-data.backup-restore', compact('backups'));
    }
}
