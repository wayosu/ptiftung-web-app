<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_mahasiswas';

    protected $fillable = [
        'nama_kegiatan',
        'slug',
        'deskripsi',
        'program_studi',
        'created_by',
        'updated_by',
    ];

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class, 'kegiatan_id');
    }

    public function createdBy()
    {
        // relasi one to one
        // setiap data KegiatanMahasiswa dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
