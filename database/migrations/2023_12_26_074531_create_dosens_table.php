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
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();
            $table->string('slug')->nullable()->unique();
            $table->string('jenis_kelamin')->nullable();
            $table->string('umur')->nullable();
            $table->string('gelar')->nullable();
            $table->string('link_gscholar')->nullable();
            $table->string('link_sinta')->nullable();
            $table->string('link_scopus')->nullable();
            $table->text('biografi')->nullable();
            $table->text('minat_penelitian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
