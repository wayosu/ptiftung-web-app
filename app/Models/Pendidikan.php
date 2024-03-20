<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    use HasFactory;

    protected $table = 'pendidikans';

    protected $fillable = [
        'dosen_id',
        'pendidikan',
    ];

    public function dosen()
    {
        // relasi one to many
        // setiap data Pendidikan dimiliki oleh satu Dosen
        return $this->belongsTo(Dosen::class, 'dosen_id', 'id');
    }
}
