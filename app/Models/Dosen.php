<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosens';

    protected $fillable = [
        'user_id',
        'slug',
        'jenis_kelamin',
        'umur',
        'jafa',
        'link_gscholar',
        'link_sinta',
        'link_scopus',
        'biografi',
        'minat_penelitian',
    ];

    public function user()
    {
        // relasi one to one
        // setiap data Dosen dimiliki oleh satu User
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pendidikans()
    {
        // relasi one to many
        // satu Dosen dapat memiliki banyak data Pendidikan.
        return $this->hasMany(Pendidikan::class);
    }

    public function bidangKepakarans()
    {
        // relasi many to many
        // setiap data Dosen dapat memiliki banyak BidangKepakaran
        /*
            - Dengan penggunaan withPivot, model menangkap atribut 
            tambahan (dalam hal ini, 'dosen_id' dan 'bidang_kepakaran_id') 
            yang ada dalam tabel pivot (tabel perantara) yang menghubungkan 
            Dosen dan BidangKepakaran.

            - Penggunaan as('bidang_kepakarans') memberi nama kepada relasi 
            many-to-many dengan pivot sehingga dapat diakses dengan nama 
            yang lebih deskriptif.
        */
        return $this->belongsToMany(BidangKepakaran::class)->withPivot('dosen_id', 'bidang_kepakaran_id')->as('bidang_kepakarans');
    }

    public function penelitians()
    {
        // relasi one to many
        // satu Dosen dapat memiliki banyak data Penelitian.
        return $this->hasMany(Penelitian::class);
    }

    public function pengabdianMasyarakats()
    {
        // relasi one to many
        // satu Dosen dapat memiliki banyak data PengabdianMasyarakat.
        return $this->hasMany(PengabdianMasyarakat::class);
    }

    public function publikasis()
    {
        // relasi one to many
        // satu Dosen dapat memiliki banyak data Publikasi.
        return $this->hasMany(Publikasi::class);
    }
}
