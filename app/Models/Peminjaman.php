<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'inventaris_id',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inventaris(): BelongsTo
    {
        return $this->belongsTo(Inventaris::class);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'aktif'
            && $this->tanggal_kembali_rencana->isPast();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aktif' => $this->isOverdue() ? 'badge-danger' : 'badge-info',
            'selesai' => 'badge-success',
            'terlambat' => 'badge-danger',
            default => 'badge-info',
        };
    }
}
