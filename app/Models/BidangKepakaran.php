<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangKepakaran extends Model
{
    use HasFactory;

    protected $table = 'bidang_kepakarans';

    protected $fillable = [
        'bidang_kepakaran',
        'slug',
        'created_by',
        'updated_by',
    ];

    public function dosen()
    {
        // relasi many to many
        // setiap BidangKepakaran dapat terkait dengan banyak Dosen, dan sebaliknya
        return $this->belongsToMany(Dosen::class);
    }

    public function createdBy()
    {
        // relasi one to one
        // setiap data BidangKepakaran dimiliki oleh satu User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
