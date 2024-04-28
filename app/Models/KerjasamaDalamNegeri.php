<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerjasamaDalamNegeri extends Model
{
    use HasFactory;

    protected $table = 'kerjasama_dalam_negeris';

    protected $fillable = [
        'instansi',
        'jenis_kegiatan',
        'tgl_mulai',
        'tgl_berakhir',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data KerjasamaDalamNegeri dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
