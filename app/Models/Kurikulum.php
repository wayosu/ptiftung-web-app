<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    use HasFactory;

    protected $table = 'kurikulums';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'sifat',
        'semester',
        'prasyarat',
        'link_gdrive',
        'created_by',  
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data Kurikulum dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }
}
