<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Karya;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    public function karyas()
    {
        return $this->hasMany(Karya::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('expired_at', '>', now())
            ->latest();
    }

    public function hasActiveSubscription()
    {
        return $this->activeSubscription()->exists();
    }

    public function getPlanName()
    {
        $subscription = $this->activeSubscription;
        return $subscription ? ucfirst($subscription->plan) : 'Basic (Free)';
    }

    public function getPlanConfig()
    {
        $subscription = $this->activeSubscription;
        
        if (!$subscription) {
            // Return free plan config
            return config('plans.free');
        }
        
        return config("plans.{$subscription->plan}");
    }

    public function canCreateDraft()
    {
        $planConfig = $this->getPlanConfig();
        $maxDrafts = $planConfig['features']['max_drafts'];
        
        // Unlimited
        if (is_null($maxDrafts)) {
            return true;
        }
        
        $currentDrafts = $this->karyas()->where('status', 'draft')->count();
        return $currentDrafts < $maxDrafts;
    }

    public function canPublish()
    {
        $planConfig = $this->getPlanConfig();
        $maxPublications = $planConfig['features']['max_publications'];
        
        // Unlimited
        if (is_null($maxPublications)) {
            return true;
        }
        
        $currentPublications = $this->karyas()->where('status', 'publish')->count();
        return $currentPublications < $maxPublications;
    }

    public function hasFeature($feature)
    {
        $planConfig = $this->getPlanConfig();
        return $planConfig['features'][$feature] ?? false;
    }

    public function getDraftLimit()
    {
        $planConfig = $this->getPlanConfig();
        $maxDrafts = $planConfig['features']['max_drafts'];
        return is_null($maxDrafts) ? '∞' : $maxDrafts;
    }

    public function getPublicationLimit()
    {
        $planConfig = $this->getPlanConfig();
        $maxPubs = $planConfig['features']['max_publications'];
        return is_null($maxPubs) ? '∞' : $maxPubs;
    }

    public function getCurrentDrafts()
    {
        return $this->karyas()->where('status', 'draft')->count();
    }

    public function getCurrentPublications()
    {
        return $this->karyas()->where('status', 'publish')->count();
    }
    public function wallet()
    {
        return $this->hasOne(\App\Models\Wallet::class);
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}