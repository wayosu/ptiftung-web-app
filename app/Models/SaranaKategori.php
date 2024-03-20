<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaranaKategori extends Model
{
    use HasFactory;

    protected $table = 'sarana_kategoris';

    protected $fillable = [
        'sarana_kategori',
        'slug',
        'created_by',
    ];

    public function saranas()
    {
        // relasi one to many
        // satu SaranaKategori dapat memiliki banyak data Sarana
        return $this->hasMany(Sarana::class);
    }

    public function createdBy()
    {
        // relasi one to one
        // setiap data SaranaKategori dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }
}
