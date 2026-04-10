<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('tipe', ['kerusakan', 'permintaan'])->default('kerusakan');
            $table->string('aset_lokasi');
            $table->string('kategori_laporan')->default('kerusakan');
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->enum('status', ['baru', 'proses', 'selesai', 'ditolak'])->default('baru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
