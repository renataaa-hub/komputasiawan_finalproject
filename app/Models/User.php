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
        return $subscription ? ucfirst($subscription->plan) : 'Free';
    }

    public function canCreateDraft()
    {
        $subscription = $this->activeSubscription;
        
        if (!$subscription) {
            // Free user: 1 draft
            return $this->karyas()->where('status', 'draft')->count() < 1;
        }
        
        return $subscription->canCreateDraft();
    }

    public function canPublish()
    {
        $subscription = $this->activeSubscription;
        
        if (!$subscription) {
            // Free user: 1 publication
            return $this->karyas()->where('status', 'publish')->count() < 1;
        }
        
        return $subscription->canPublish();
    }

    public function hasFeature($feature)
    {
        $subscription = $this->activeSubscription;
        return $subscription ? $subscription->hasFeature($feature) : false;
    }
}
