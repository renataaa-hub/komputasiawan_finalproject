<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan',
        'price',
        'status',
        'order_id',
        'transaction_id',
        'payment_type',
        'started_at',
        'expired_at',
        'midtrans_response'
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'started_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active' && $this->expired_at > now();
    }

    public function activate()
    {
        $this->update([
            'status' => 'active',
            'started_at' => now(),
            'expired_at' => now()->addDays(config("plans.{$this->plan}.duration_days", 30))
        ]);
    }

    public function getPlanConfig()
    {
        return config("plans.{$this->plan}");
    }

    public function canCreateDraft()
    {
        $config = $this->getPlanConfig();
        $maxDrafts = $config['features']['max_drafts'];
        
        // Unlimited
        if (is_null($maxDrafts)) {
            return true;
        }
        
        $currentDrafts = $this->user->karyas()->where('status', 'draft')->count();
        return $currentDrafts < $maxDrafts;
    }

    public function canPublish()
    {
        $config = $this->getPlanConfig();
        $maxPublications = $config['features']['max_publications'];
        
        // Unlimited
        if (is_null($maxPublications)) {
            return true;
        }
        
        $currentPublications = $this->user->karyas()->where('status', 'publish')->count();
        return $currentPublications < $maxPublications;
    }

    public function hasFeature($feature)
    {
        $config = $this->getPlanConfig();
        return $config['features'][$feature] ?? false;
    }
}