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
        Schema::create('data_dukung_akreditasis', function (Blueprint $table) {
            $table->id();
            $table->enum('program_studi', ['SISTEM INFORMASI', 'PEND. TEKNOLOGI INFORMASI'])->default('SISTEM INFORMASI');
            $table->string('nomor_butir');
            $table->string('keterangan');
            $table->string('kategori');
            $table->string('dokumen')->nullable();
            $table->string('link_dokumen')->nullable();
            $table->foreignId('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->default(null)->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_dukung_akreditasis');
    }
};
