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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('related_id');
            $table->string('related_type');
            $table->text('pesan');
            $table->boolean('dibaca')->default(false); // Kolom untuk menandai apakah notifikasi telah dibaca
            $table->timestamps();

            // Indeks untuk kunci asing
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Tambahkan indeks pada kolom 'dibaca' untuk keperluan pencarian
            $table->index('dibaca');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
