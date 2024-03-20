<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswas';

    protected $fillable = [
        'user_id',
        'program_studi',
        'angkatan'
    ];

    public function user()
    {
        // relasi one to one
        // setiap data Mahasiswa dimiliki oleh satu User
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
