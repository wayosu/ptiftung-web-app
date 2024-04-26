<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nim',
        'nip',
        'hak_akses_khusus',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function mahasiswa()
    {
        // relasi one to one
        // satu User hanya memiliki satu data Mahasiswa
        return $this->hasOne(Mahasiswa::class, 'user_id', 'id');
    }

    public function dosen()
    {
        // relasi one to one
        // satu User hanya memiliki satu data Dosen
        return $this->hasOne(Dosen::class, 'user_id', 'id');
    }

    public function bidangKepakarans()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data BidangKepakaran
        return $this->hasMany(BidangKepakaran::class, 'created_by', 'id');
    }

    public function saranaKategoris()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data SaranaKategori
        return $this->hasMany(SaranaKategori::class, 'created_by', 'id');
    }

    public function saranas()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data Sarana
        return $this->hasMany(Sarana::class, 'created_by', 'id');
    }

    public function prasaranaKategoris()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data SaranaKategori
        return $this->hasMany(PrasaranaKategori::class, 'created_by', 'id');
    }

    public function prasaranas()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data Prasarana
        return $this->hasMany(Prasarana::class, 'created_by', 'id');
    }

    public function sistemInformasis()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data SistemInformasi
        return $this->hasMany(SistemInformasi::class, 'created_by', 'id');
    }

    public function profilLulusans()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data ProfilLulusan
        return $this->hasMany(ProfilLulusan::class, 'created_by', 'id');
    }

    public function capaianPembelajarans()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data CapaianPembelajaran
        return $this->hasMany(CapaianPembelajaran::class, 'created_by', 'id');
    }

    public function kurikulums()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data Kurikulum
        return $this->hasMany(Kurikulum::class, 'created_by', 'id');
    }

    public function dokumenKurikulums()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data DokumenKurikulum
        return $this->hasMany(DokumenKurikulum::class, 'created_by', 'id');
    }

    public function kalenderAkademiks()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data KalenderAkademik
        return $this->hasMany(KalenderAkademik::class, 'created_by', 'id');
    }

    public function kegiatanPerkuliahans()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data KegiatanPerkuliahan
        return $this->hasMany(KegiatanPerkuliahan::class, 'created_by', 'id');
    }

    public function penelitians()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data Penelitian
        return $this->hasMany(Penelitian::class, 'created_by', 'id');
    }
}
