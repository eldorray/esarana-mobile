<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kategori extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'parent_id',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Kategori::class, 'parent_id');
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
