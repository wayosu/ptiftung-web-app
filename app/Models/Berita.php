<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'beritas';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'thumbnail',
        'status',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data Berita dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
