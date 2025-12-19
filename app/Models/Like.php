<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'karya_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function karya()
    {
        return $this->belongsTo(Karya::class);
    }
}