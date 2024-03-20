<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaranaTemporaryImage extends Model
{
    use HasFactory;

    protected $table = 'sarana_temporary_images';

    protected $fillable = [
        'file',
        'user_id',
    ];
}
