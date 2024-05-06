<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    protected $table = 'jurnals';

    protected $fillable = [
        'judul_jurnal',
        'link_jurnal',
        'created_by',
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data Jurnal dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }
}
