<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publikasi extends Model
{
    use HasFactory;

    protected $table = 'publikasis';

    protected $fillable = [
        'dosen_id',
        'judul',
        'link_publikasi',
        'created_by',
        'updated_by',
    ];

    public function dosen()
    {
        // relasi one to many
        // setiap data Publikasi dimiliki oleh satu Dosen
        return $this->belongsTo(Dosen::class, 'dosen_id', 'id');
    }

    public function createdBy()
    {
        // relasi one to one
        // setiap data Publikasi dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
