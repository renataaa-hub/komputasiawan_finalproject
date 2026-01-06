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
        'status_monetisasi',
        'harga',
        'pendapatan',
    ];
    public function collaborators()
{
    return $this->belongsToMany(\App\Models\User::class, 'karya_collaborators')
        ->withPivot('role')
        ->withTimestamps();
}


// app/Models/Karya.php

public function payoutBlockSize(): int
{
    return 1000;
}

public function payoutPerBlock(): int
{
    return 50000;
}

/**
 * Berapa blok 1000 views yang sudah tercapai
 * contoh: 0-999 => 0, 1000-1999 => 1, 2000-2999 => 2, dst.
 */
public function earnedBlocks(): int
{
    $views = (int) ($this->views_count ?? $this->views ?? 0);
    return intdiv($views, $this->payoutBlockSize());
}

/**
 * Berapa blok yang bisa diklaim (earnedBlocks - claimed_blocks)
 */
public function claimableBlocks(): int
{
    $claimed = (int) ($this->claimed_blocks ?? 0);
    return max(0, $this->earnedBlocks() - $claimed);
}

/**
 * Total pendapatan dari semua blok yang sudah tercapai
 */
public function totalEarnedAmount(): int
{
    return $this->earnedBlocks() * $this->payoutPerBlock();
}

/**
 * Pendapatan yang bisa diklaim sekarang
 */
public function claimableAmount(): int
{
    return $this->claimableBlocks() * $this->payoutPerBlock();
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