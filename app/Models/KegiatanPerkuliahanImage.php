<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanPerkuliahanImage extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_perkuliahan_images';

    protected $fillable = [
        'kegiatan_perkuliahan_id',
        'gambar',
    ];

    public function kegiatanPerkuliahan() {
        // relasi one to one
        // setiap data KegiatanPerkuliahanImage dimiliki oleh satu KegiatanPerkuliahan
        return $this->belongsTo(KegiatanPerkuliahan::class);
    }
}
