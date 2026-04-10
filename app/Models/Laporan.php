<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    protected $fillable = [
        'user_id',
        'nama_pelapor',
        'kontak_pelapor',
        'tipe',
        'aset_lokasi',
        'kategori_laporan',
        'deskripsi',
        'foto',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the display name of the reporter.
     * If user is logged in, show user name. Otherwise show nama_pelapor.
     */
    public function getPelaporNameAttribute(): string
    {
        return $this->user?->name ?? $this->nama_pelapor ?? 'Anonim';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'baru' => 'badge-info',
            'proses' => 'badge-warning',
            'selesai' => 'badge-success',
            'ditolak' => 'badge-danger',
            default => 'badge-info',
        };
    }
}
