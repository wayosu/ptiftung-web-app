<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangKepakaran extends Model
{
    use HasFactory;

    protected $table = 'bidang_kepakarans';

    protected $fillable = [
        'bidang_kepakaran',
        'slug',
    ];

    public function dosen()
    {
        // relasi many to many
        // setiap BidangKepakaran dapat terkait dengan banyak Dosen, dan sebaliknya
        return $this->belongsToMany(Dosen::class);
    }
}
