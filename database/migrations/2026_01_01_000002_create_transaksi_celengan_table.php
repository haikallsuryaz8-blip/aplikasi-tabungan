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
        Schema::create('transaksi_celengan', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_target');
            $table->enum('jenis_transaksi', ['SETOR', 'TARIK']);
            $table->decimal('nominal', 15, 2);
            $table->string('sumber_dana')->default('Uang Saku'); // Gaji, Uang Saku, Freelance, Hadiah, Sisa Belanja
            $table->date('tanggal_transaksi');
            $table->string('keterangan')->nullable();
            $table->decimal('saldo_sebelum', 15, 2)->default(0);
            $table->decimal('saldo_sesudah', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_target')->references('id_target')->on('target_tabungan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_celengan');
    }
};
