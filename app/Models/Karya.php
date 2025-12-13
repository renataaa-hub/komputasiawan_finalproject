<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karya extends Model
{
    use HasFactory;

    protected $table = 'karya';

    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'konten',
        'kategori',
        'status',
        'is_draft',
        'akses',
        'thumbnail',
    ];
}
