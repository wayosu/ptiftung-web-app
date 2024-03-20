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

    public function saranaKategoris()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data SaranaKategori
        return $this->hasMany(SaranaKategori::class, 'created_by', 'id');
    }

    public function sistemInformasis()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data SistemInformasi
        return $this->hasMany(SistemInformasi::class, 'created_by', 'id');
    }
}
