<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruangan extends Model
{
    protected $fillable = [
        'lokasi_id',
        'nama',
        'lantai',
        'kapasitas',
        'status',
    ];

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function inventaris(): HasMany
    {
        return $this->hasMany(Inventaris::class);
    }

    public function bahanHabisPakais(): HasMany
    {
        return $this->hasMany(BahanHabisPakai::class);
    }
}
