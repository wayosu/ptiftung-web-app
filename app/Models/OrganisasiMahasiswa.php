<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisasiMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'organisasi_mahasiswas';

    protected $fillable = [
        'nama_organisasi',
        'slug',
        'tingkat_organisasi',
        'deskripsi',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        // relasi one to one
        // setiap data OrganisasiMahasiswa dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
