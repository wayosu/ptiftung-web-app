<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswas';

    protected $fillable = [
        'mahasiswa_id',
        'program_studi',
        'angkatan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id', 'id');
    }
}
