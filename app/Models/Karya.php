<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karya extends Model
{
    use HasFactory;

    protected $table = 'karyas';

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
    public function collaborators()
{
    return $this->belongsToMany(\App\Models\User::class, 'karya_collaborators')
        ->withPivot('role')
        ->withTimestamps();
}

public function isCollaborator($user): bool
{
    if (!$user) return false;

    return $this->collaborators()
        ->where('users.id', $user->id)
        ->exists();
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    // Helper method
    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function commentsCount()
    {
        return $this->comments()->count();
    }
}