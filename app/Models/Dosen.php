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
        'link_gscholar',
        'link_sinta',
        'link_scopus',
        'img',
        'biografi',
        'minat_penelitian',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bidangKepakaran()
    {
        return $this->belongsToMany(BidangKepakaran::class);
    }
}
