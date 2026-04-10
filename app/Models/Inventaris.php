<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventaris extends Model
{
    protected $table = 'inventaris';

    protected $fillable = [
        'nama',
        'kode',
        'kategori_id',
        'ruangan_id',
        'deskripsi',
        'serial_number',
        'kondisi',
        'status',
        'gambar',
        'nilai_aset',
        'tanggal_perolehan',
    ];

    protected $casts = [
        'nilai_aset' => 'decimal:2',
        'tanggal_perolehan' => 'date',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'tersedia' => 'badge-success',
            'dipinjam' => 'badge-info',
            'maintenance' => 'badge-warning',
            'rusak' => 'badge-danger',
            default => 'badge-info',
        };
    }
}
