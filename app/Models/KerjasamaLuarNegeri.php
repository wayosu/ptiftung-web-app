<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerjasamaLuarNegeri extends Model
{
    use HasFactory;

    protected $table = 'kerjasama_luar_negeris';

    protected $fillable = [
        'instansi',
        'jenis_kegiatan',
        'tgl_mulai',
        'tgl_berakhir',
        'program_studi',
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
