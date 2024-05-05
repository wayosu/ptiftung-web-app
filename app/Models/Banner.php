<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'gambar',
        'created_by',
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data Banner dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }
}
