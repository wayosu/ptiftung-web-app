<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosens';

    protected $fillable = [
        'dosen_id',
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
        return $this->belongsTo(User::class, 'dosen_id', 'id');
    }

    public function bidangKepakaran()
    {
        return $this->belongsToMany(BidangKepakaran::class);
    }
}
