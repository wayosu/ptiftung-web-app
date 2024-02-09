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
        'jenis_kelamin',
        'umur',
        'gelar',
        'link_gscholar',
        'link_sinta',
        'link_scopus',
        'biografi',
        'minat_penelitian',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pendidikan()
    {
        return $this->hasMany(Pendidikan::class);
    }

    public function bidangKepakaran()
    {
        return $this->belongsToMany(BidangKepakaran::class)->withPivot('dosen_id', 'bidang_kepakaran_id')->as('bidang_kepakarans');
    }
}
