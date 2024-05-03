<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenKebijakan extends Model
{
    use HasFactory;

    protected $table = 'dokumen_kebijakans';

    protected $fillable = [
        'keterangan',
        'kategori',
        'dokumen',
        'link_dokumen',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data DokumenKebijakan dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
