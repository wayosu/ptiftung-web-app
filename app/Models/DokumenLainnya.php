<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenLainnya extends Model
{
    use HasFactory;

    protected $table = 'dokumen_lainnyas';

    protected $fillable = [
        'program_studi',
        'keterangan',
        'dokumen',
        'link_dokumen',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data DokumenLainnya dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
