<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenKurikulum extends Model
{
    use HasFactory;

    protected $table = "dokumen_kurikulums";

    protected $fillable = [
        'keterangan',
        'link_gdrive',
        'active',
        'program_studi',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data DokumenKurikulum dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
