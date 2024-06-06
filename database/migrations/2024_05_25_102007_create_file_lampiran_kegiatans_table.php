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
        Schema::create('file_lampiran_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lampiran_kegiatan_id');
            $table->string('file_path');
            $table->string('file_name');
            $table->timestamps();

            // indeks untuk kunci asing
            $table->foreign('lampiran_kegiatan_id')->references('id')->on('lampiran_kegiatans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_lampiran_kegiatans');
    }
};
