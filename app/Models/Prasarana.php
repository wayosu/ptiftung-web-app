<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prasarana extends Model
{
    use HasFactory;

    protected $table = 'prasaranas';

    protected $fillable = [
        'keterangan',
        'slug',
        'prasarana_kategori_id',
        'created_by',
    ];

    public function prasaranaKategori()
    {
        // relasi one to one
        // setiap data Prasarana dimiliki oleh satu PrasaranaKategori
        return $this->belongsTo(PrasaranaKategori::class, 'prasarana_kategori_id');
    }

    public function prasaranaImages()
    {
        // relasi one to many
        // satu Prasarana dapat memiliki banyak data PrasaranaImage
        return $this->hasMany(PrasaranaImage::class);
    }

    public function createdBy()
    {
        // relasi one to one
        // setiap data Prasarana dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }
}
