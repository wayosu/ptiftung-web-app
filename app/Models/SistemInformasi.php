<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SistemInformasi extends Model
{
    use HasFactory;

    protected $table = 'sistem_informasis';

    protected $fillable = [
        'sistem_informasi',
        'link',
        'created_by'
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data SistemInformasi dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }
}
