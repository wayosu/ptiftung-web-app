<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KalenderAkademik extends Model
{
    use HasFactory;

    protected $table = 'kalender_akademiks';

    protected $fillable = [
        'kegiatan',
        'waktu',
        'created_by',
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data KalenderAkademik dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }
}
