<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeDanDoubleDegree extends Model
{
    use HasFactory;

    protected $table = 'exchange_dan_double_degrees';

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
        // setiap data ExchangeDanDoubleDegree dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
