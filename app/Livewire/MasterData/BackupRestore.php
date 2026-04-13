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

    // Tabel yang di-reset (kecuali kategoris, users, roles, permissions)
    const RESET_TABLES = [
        'inventaris',
        'bahan_habis_pakais',
        'peminjamans',
        'laporans',
        'lokasis',
        'ruangans',
        'audit_logs',
    ];

    private function dbConfig(): array
    {
        return [
            'host'     => config('database.connections.mysql.host', '127.0.0.1'),
            'port'     => config('database.connections.mysql.port', '3306'),
            'database' => config('database.connections.mysql.database'),
            'username' => config('database.connections.mysql.username'),
            'password' => config('database.connections.mysql.password'),
        ];
    }

    private function backupDir(): string
    {
        $dir = storage_path('app/backups');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    public function downloadBackup()
    {
        $cfg      = $this->dbConfig();
        $filename = 'backup_esarpras_' . now()->format('Ymd_His') . '.sql';
        $path     = $this->backupDir() . '/' . $filename;

        // Cek apakah mysqldump tersedia
        exec('which mysqldump 2>/dev/null', $out, $code);
        $mysqldump = $code === 0 ? trim($out[0]) : 'mysqldump';

        $password = str_replace("'", "'\\''", $cfg['password']);

        $cmd = sprintf(
            "MYSQL_PWD='%s' %s --host=%s --port=%s --user=%s --single-transaction --routines --triggers %s > %s 2>&1",
            $password,
            escapeshellcmd($mysqldump),
            escapeshellarg($cfg['host']),
            escapeshellarg($cfg['port']),
            escapeshellarg($cfg['username']),
            escapeshellarg($cfg['database']),
            escapeshellarg($path)
        );

        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0 || ! file_exists($path) || filesize($path) < 100) {
            // Fallback: generate SQL manual via PHP jika mysqldump tidak tersedia
            $this->generateSqlManual($path);
        }

        if (! file_exists($path) || filesize($path) < 10) {
            session()->flash('error', 'Gagal membuat backup. Periksa konfigurasi database.');
            return;
        }

        return response()->download($path, $filename, [
            'Content-Type'        => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ])->deleteFileAfterSend(true);
    }

    private function generateSqlManual(string $path): void
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $key    = 'Tables_in_' . $dbName;

        $sql  = "-- e-SARPRAS Database Backup\n";
        $sql .= "-- Generated: " . now()->toDateTimeString() . "\n";
        $sql .= "-- Database: {$dbName}\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $tableObj) {
            $table = $tableObj->$key;

            // CREATE TABLE
            $create = DB::select("SHOW CREATE TABLE `{$table}`");
            $sql   .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $sql   .= $create[0]->{'Create Table'} . ";\n\n";

            // INSERT DATA
            $rows = DB::table($table)->get();
            if ($rows->isNotEmpty()) {
                $cols  = array_keys((array) $rows->first());
                $colList = implode('`, `', $cols);
                $sql  .= "INSERT INTO `{$table}` (`{$colList}`) VALUES\n";
                $values = $rows->map(function ($row) {
                    $escaped = array_map(function ($v) {
                        if (is_null($v)) return 'NULL';
                        return "'" . addslashes((string) $v) . "'";
                    }, (array) $row);
                    return '(' . implode(', ', $escaped) . ')';
                });
                $sql .= $values->implode(",\n") . ";\n\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        file_put_contents($path, $sql);
    }

    public function restoreBackup()
    {
        $this->validate([
            'backupFile' => 'required|file|max:102400',
        ], [
            'backupFile.required' => 'Pilih file backup SQL terlebih dahulu.',
            'backupFile.max'      => 'Ukuran file maksimal 100MB.',
        ]);

        $tmpPath = $this->backupFile->getRealPath();
        $content = file_get_contents($tmpPath, false, null, 0, 100);

        // Validasi: harus file SQL
        if (stripos($content, 'CREATE TABLE') === false &&
            stripos($content, 'INSERT INTO') === false &&
            stripos($content, '-- e-SARPRAS') === false &&
            stripos($content, '-- MySQL') === false &&
            stripos($content, '-- MariaDB') === false) {
            session()->flash('error', 'File bukan backup SQL yang valid.');
            return;
        }

        // Backup otomatis sebelum restore
        $autoPath = $this->backupDir() . '/pre_restore_' . now()->format('Ymd_His') . '.sql';
        $this->generateSqlManual($autoPath);

        // Jalankan SQL dari file backup
        $sql = file_get_contents($tmpPath);
        try {
            DB::unprepared($sql);
        } catch (\Throwable $e) {
            session()->flash('error', 'Restore gagal: ' . $e->getMessage());
            return;
        }

        $this->reset('backupFile');
        session()->flash('success', 'Database berhasil di-restore dari backup.');
    }

    public function resetData()
    {
        if ($this->resetKonfirmasi !== 'RESET') {
            session()->flash('error', 'Ketik RESET untuk konfirmasi.');
            return;
        }

        // Backup otomatis sebelum reset
        $autoPath = $this->backupDir() . '/pre_reset_' . now()->format('Ymd_His') . '.sql';
        $this->generateSqlManual($autoPath);

        // Hapus file storage (foto inventaris, laporan, dll)
        foreach (['inventaris', 'laporan', 'bahan'] as $folder) {
            if (Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->deleteDirectory($folder);
            }
        }

        // Truncate tabel — matikan FK dulu
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach (self::RESET_TABLES as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->reset('confirmReset', 'resetKonfirmasi');
        session()->flash('success', 'Data operasional berhasil direset. Kategori, user, dan role tetap terjaga.');
    }

    public function render()
    {
        $backups = collect();
        $backupDir = $this->backupDir();
        $files = array_merge(
            glob($backupDir . '/*.sql') ?: [],
            glob($backupDir . '/*.sql.gz') ?: []
        );
        rsort($files);
        $backups = collect(array_slice($files, 0, 10))->map(fn($f) => [
            'name' => basename($f),
            'size' => filesize($f) > 1048576
                ? round(filesize($f) / 1048576, 1) . ' MB'
                : round(filesize($f) / 1024, 1) . ' KB',
            'time' => date('d M Y H:i', filemtime($f)),
        ]);

        return view('livewire.master-data.backup-restore', compact('backups'));
    }
}
