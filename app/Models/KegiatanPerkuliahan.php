<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanPerkuliahan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_perkuliahans';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'thumbnail',
        'link_video',
        'created_by',
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data KegiatanPerkuliahan dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }
}
