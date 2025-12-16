<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karya extends Model
{
    use HasFactory;

    protected $table = 'karyas'; // ← Tambahkan ini untuk pakai tabel 'karyas'

    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'jenis',
        'deskripsi',
        'konten',
        'kategori',
        'status',
        'is_draft',
        'akses',
        'cover',
        'thumbnail',
        'status_monetisasi',
        'harga',
        'pendapatan',
        'views',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}