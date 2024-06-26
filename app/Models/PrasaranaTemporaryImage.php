<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrasaranaTemporaryImage extends Model
{
    use HasFactory;

    protected $table = 'prasarana_temporary_images';

    protected $fillable = [
        'file',
        'user_id',
    ];
}
