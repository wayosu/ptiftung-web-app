<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penelitian extends Model
{
    use HasFactory;

    protected $table = 'penelitians';

    protected $fillable = [
        'tahun',
        'dosen_id',
        'jabatan',
        'skim',
        'judul',
        'sumber_dana',
        'jumlah_dana',
        'created_by',
        'updated_by',
    ];

    public function dosen()
    {
        // relasi one to many
        // setiap data Penelitian dimiliki oleh satu Dosen
        return $this->belongsTo(Dosen::class, 'dosen_id', 'id');
    }

    public function createdBy()
    {
        // relasi one to one
        // setiap data Penelitian dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
