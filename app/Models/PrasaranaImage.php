<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrasaranaImage extends Model
{
    use HasFactory;

    protected $table = 'prasarana_images';

    protected $fillable = [
        'sarana_id',
        'gambar',
    ];

    public function prasarana() {
        // relasi one to one
        // setiap data PrasaranaImage dimiliki oleh satu Prasarana
        return $this->belongsTo(Prasarana::class);
    }
}
