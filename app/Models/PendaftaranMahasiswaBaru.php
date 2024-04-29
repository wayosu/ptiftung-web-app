<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranMahasiswaBaru extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_mahasiswa_barus';

    protected $fillable = [
        'singkatan',
        'kepanjangan',
        'deskripsi',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data PendaftaranMahasiswaBaru dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
