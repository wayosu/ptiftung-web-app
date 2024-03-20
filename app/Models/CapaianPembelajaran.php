<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapaianPembelajaran extends Model
{
    use HasFactory;

    protected $table = 'capaian_pembelajarans';

    protected $fillable = [
        'capaian_pembelajaran',
        'created_by'
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data Prasarana dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }
}
