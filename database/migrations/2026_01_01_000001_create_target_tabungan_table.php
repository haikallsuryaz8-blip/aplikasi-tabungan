<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('target_tabungan', function (Blueprint $table) {
            $table->id('id_target');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nama_target');
            $table->string('kategori')->default('Lainnya'); // Elektronik, Kendaraan, Liburan, Dana Darurat, Investasi
            $table->decimal('target_nominal', 15, 2);
            $table->decimal('saldo_terkumpul', 15, 2)->default(0);
            $table->date('tenggat_waktu')->nullable();
            $table->string('ikon')->default('fa-piggy-bank');
            $table->string('warna')->default('blue'); // blue, emerald, purple, amber, rose
            $table->enum('status', ['aktif', 'tercapai', 'dibatalkan'])->default('aktif');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_tabungan');
    }
};
