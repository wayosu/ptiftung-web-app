<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilProgramStudi extends Model
{
    use HasFactory;

    protected $table = 'profil_program_studis';

    protected $fillable = [
        'nama_program_studi',
        'nama_dasbor',
        'logo',
        'link_embed_video_profil',
        'sejarah',
        'visi_keilmuan',
        'tujuan',
        'strategi',
        'struktur_organisasi',
        'nomor_telepon',
        'email',
        'link_facebook',
        'link_instagram',
        'alamat',
        'link_embed_gmaps',
    ];
}
