<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilLulusan extends Model
{
    use HasFactory;

    protected $table = 'profil_lulusans';

    protected $fillable = [
        'judul',
        'subjudul',
        'deskripsi',
        'gambar',
        'created_by',
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data Profil Lulusan dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }
}
