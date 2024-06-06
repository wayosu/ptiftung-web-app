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
        Schema::create('profil_program_studis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_program_studi')->nullable();
            $table->string('nama_dasbor')->nullable();
            $table->enum('program_studi', ['SISTEM INFORMASI', 'PEND. TEKNOLOGI INFORMASI'])->default('SISTEM INFORMASI');
            $table->string('logo')->nullable();
            $table->text('link_embed_video_profil')->nullable();
            $table->text('sejarah')->nullable();
            $table->text('visi_keilmuan')->nullable();
            $table->text('tujuan')->nullable();
            $table->text('strategi')->nullable();
            $table->string('struktur_organisasi')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('link_facebook')->nullable();
            $table->text('link_instagram')->nullable();
            $table->text('alamat')->nullable();
            $table->text('link_embed_gmaps')->nullable();
            $table->foreignId('updated_by')->nullable()->default(null)->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_program_studis');
    }
};
