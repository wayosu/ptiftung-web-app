<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sarana extends Model
{
    use HasFactory;

    protected $table = 'saranas';

    protected $fillable = [
        'keterangan',
        'slug',
        'sarana_kategori_id',
        'created_by',
    ];

    public function saranaKategori()
    {
        // relasi one to one
        // setiap data Sarana dimiliki oleh satu SaranaKategori
        return $this->belongsTo(SaranaKategori::class, 'sarana_kategori_id');
    }

    public function saranaImages()
    {
        // relasi one to many
        // satu Sarana dapat memiliki banyak data SaranaImage
        return $this->hasMany(SaranaImage::class);
    }

    public function createdBy()
    {
        // relasi one to one
        // setiap data Sarana dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }
}
