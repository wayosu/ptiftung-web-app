<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosens';

    protected $fillable = [
        'user_id',
        'slug',
        'jk',
        'umur',
        'gelar',
        'bidang',
        'link_gscholar',
        'link_sinta',
        'link_scopus',
        'img',
        'biografi',
        'minat_penelitian',
    ];
}
