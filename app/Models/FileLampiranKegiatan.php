<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLampiranKegiatan extends Model
{
    use HasFactory;

    protected $table = 'file_lampiran_kegiatans';

    protected $fillable = [
        'lampiran_kegiatan_id', 
        'file_path',
        'file_name',
    ];

    public function lampiranKegiatan()
    {
        return $this->belongsTo(LampiranKegiatan::class, 'lampiran_kegiatan_id', 'id');
    }
}
