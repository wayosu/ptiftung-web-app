<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagangAtauPraktikIndustri extends Model
{
    use HasFactory;

    protected $table = 'magang_atau_praktik_industris';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'gambar',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data MagangAtauPraktikIndustri dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
