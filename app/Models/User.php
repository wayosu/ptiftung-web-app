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

    public function memilikiPeran($role)
    {
        return $this->roles->contains('name', $role);
    }

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

    public function kegiatanMahasiswas()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data KegiatanMahasiswa
        return $this->hasMany(KegiatanMahasiswa::class, 'created_by', 'id');
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

    public function kerjasamaDalamNegeris()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data KerjasamaDalamNegeri
        return $this->hasMany(KerjasamaDalamNegeri::class, 'created_by', 'id');
    }

    public function kerjasamaLuarNegeris()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data KerjasamaLuarNegeri
        return $this->hasMany(KerjasamaLuarNegeri::class, 'created_by', 'id');
    }

    public function pendaftaranMahasiswaBarus()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data PendaftaranMahasiswaBaru
        return $this->hasMany(PendaftaranMahasiswaBaru::class, 'created_by', 'id');
    }

    public function prestasiMahasiswas()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data PrestasiMahasiswa
        return $this->hasMany(PrestasiMahasiswa::class, 'created_by', 'id');
    }

    public function beasiswas()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data Beasiswa
        return $this->hasMany(Beasiswa::class, 'created_by', 'id');
    }

    public function exchangeDanDoubleDegrees()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data ExchangeDanDoubleDegree
        return $this->hasMany(ExchangeDanDoubleDegree::class, 'created_by', 'id');
    }

    public function seminarDanKompetisis()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data SeminarDanKompetisi
        return $this->hasMany(SeminarDanKompetisi::class, 'created_by', 'id');
    }

    public function magangAtauPraktikIndustris()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data MagangAtauPraktikIndustri
        return $this->hasMany(MagangAtauPraktikIndustri::class, 'created_by', 'id');
    }

    public function lowonganKerjas()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data LowonganKerja
        return $this->hasMany(LowonganKerja::class, 'created_by', 'id');
    }

    public function organisasiMahasiswas()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data OrganisasiMahasiswa
        return $this->hasMany(OrganisasiMahasiswa::class, 'created_by', 'id');
    }

    public function dokumenKebijakans()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data DokumenKebijakan
        return $this->hasMany(DokumenKebijakan::class, 'created_by', 'id');
    }

    public function dokumenLainnyas()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data DokumenLainnya
        return $this->hasMany(DokumenLainnya::class, 'created_by', 'id');
    }

    public function dataDukungAkreditasis()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data DataDukungAkreditasi
        return $this->hasMany(DataDukungAkreditasi::class, 'created_by', 'id');
    }

    public function banners()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data Banner
        return $this->hasMany(Banner::class, 'created_by', 'id');
    }

    public function beritas()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data Berita
        return $this->hasMany(Berita::class, 'created_by', 'id');
    }

    public function agendas()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data Agenda
        return $this->hasMany(Agenda::class, 'created_by', 'id');
    }

    public function jurnals()
    {
        // relasi one to many
        // satu User dapat memiliki banyak data Journal
        return $this->hasMany(Jurnal::class, 'created_by', 'id');
    }
}
