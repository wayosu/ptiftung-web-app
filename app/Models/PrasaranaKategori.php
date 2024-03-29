<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrasaranaKategori extends Model
{
    use HasFactory;

    protected $table = 'prasarana_kategoris';

    protected $fillable = [
        'prasarana_kategori',
        'slug',
        'created_by',
        'updated_by'
    ];

    public function prasaranas()
    {
        // relasi one to many
        // satu PrasaranaKategori dapat memiliki banyak data Prasarana
        return $this->hasMany(prasarana::class);
    }

    public function createdBy()
    {
        // relasi one to one
        // setiap data PrasaranaKategori dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
