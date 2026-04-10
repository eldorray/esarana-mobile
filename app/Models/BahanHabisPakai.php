<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BahanHabisPakai extends Model
{
    protected $table = 'bahan_habis_pakais';

    protected $fillable = [
        'nama',
        'kode',
        'kategori_id',
        'ruangan_id',
        'stok',
        'satuan',
        'stok_minimum',
        'harga_satuan',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function isStokRendah(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }
}
