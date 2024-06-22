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
        Schema::disableForeignKeyConstraints();

        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index();
            $table->foreignId('bank_unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warga_id')->constrained()->cascadeOnDelete();
            $table->integer('berat');
            $table->string('kategori');
            $table->string('status');
            $table->dateTime('tanggal');
            $table->bigInteger('price')->default(5000);
            $table->foreignId('warga_bank_unit_id');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
