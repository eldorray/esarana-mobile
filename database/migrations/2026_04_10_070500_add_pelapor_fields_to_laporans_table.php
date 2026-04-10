<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->string('nama_pelapor')->nullable()->after('user_id');
            $table->string('kontak_pelapor')->nullable()->after('nama_pelapor');

            // Make user_id nullable for public reports
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['nama_pelapor', 'kontak_pelapor']);
        });
    }
};
