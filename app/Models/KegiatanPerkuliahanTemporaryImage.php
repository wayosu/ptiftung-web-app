<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanPerkuliahanTemporaryImage extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_perkuliahan_temporary_images';

    protected $fillable = [
        'file',
        'user_id',
    ];
}
