<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karya extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',
        'jenis',
        'deskripsi',
        'isi',
        'status',
        'status_monetisasi',
        'harga',
        'pendapatan'
    ];
}
