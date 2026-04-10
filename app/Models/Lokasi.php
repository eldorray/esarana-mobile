<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lokasi extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'tipe',
        'status',
        'gambar',
        'latitude',
        'longitude',
    ];

    public function ruangans(): HasMany
    {
        return $this->hasMany(Ruangan::class);
    }

    public function getTotalItemAttribute(): int
    {
        return $this->ruangans->sum(function ($ruangan) {
            return $ruangan->inventaris->count() + $ruangan->bahanHabisPakais->count();
        });
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'operasional' => 'badge-success',
            'renovasi' => 'badge-warning',
            'nonaktif' => 'badge-danger',
            default => 'badge-info',
        };
    }
}
