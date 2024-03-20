<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaranaImage extends Model
{
    use HasFactory;

    protected $table = 'sarana_images';

    protected $fillable = [
        'sarana_id',
        'gambar',
    ];

    public function sarana()
    {
        // relasi one to one
        // setiap data SaranaImage dimiliki oleh satu Sarana
        return $this->belongsTo(Sarana::class);
    }
}
