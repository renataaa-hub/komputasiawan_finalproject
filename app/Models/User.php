<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // RELATIONS

    public function karyas()
    {
        return $this->hasMany(Karya::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Alias untuk dipakai di admin/users.blade.php
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    /**
     * Subscription aktif (status active & belum expired).
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->whereNotNull('expired_at')
            ->where('expired_at', '>', now())
            ->latestOfMany();
    }
    public function latestSubscription()
{
    return $this->hasOne(Subscription::class)->latestOfMany();
}

    // HELPERS

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    public function getPlanName(): string
    {
        $sub = $this->activeSubscription()->first();
        return $sub ? ucfirst($sub->plan) : 'Basic (Free)';
    }

    public function getPlanConfig(): array
    {
        $sub = $this->activeSubscription()->first();

        if (!$sub) {
         
            return config('plans.free');
        }

        return config("plans.{$sub->plan}");
    }

    public function canCreateDraft(): bool
    {
        $planConfig = $this->getPlanConfig();
        $maxDrafts = $planConfig['features']['max_drafts'] ?? 0;

        if (is_null($maxDrafts)) return true;

        $currentDrafts = $this->karyas()->where('status', 'draft')->count();
        return $currentDrafts < (int) $maxDrafts;
    }

    public function canPublish(): bool
    {
        $planConfig = $this->getPlanConfig();
        $maxPubs = $planConfig['features']['max_publications'] ?? 0;

        if (is_null($maxPubs)) return true;

        $currentPubs = $this->karyas()->where('status', 'publish')->count();
        return $currentPubs < (int) $maxPubs;
    }

    public function hasFeature(string $feature): bool
    {
        $planConfig = $this->getPlanConfig();
        return (bool) ($planConfig['features'][$feature] ?? false);
    }

    public function getDraftLimit()
    {
        $planConfig = $this->getPlanConfig();
        $maxDrafts = $planConfig['features']['max_drafts'] ?? 0;
        return is_null($maxDrafts) ? '∞' : (int) $maxDrafts;
    }

    public function getPublicationLimit()
    {
        $planConfig = $this->getPlanConfig();
        $maxPubs = $planConfig['features']['max_publications'] ?? 0;
        return is_null($maxPubs) ? '∞' : (int) $maxPubs;
    }

    public function getCurrentDrafts(): int
    {
        return $this->karyas()->where('status', 'draft')->count();
    }

    public function getCurrentPublications(): int
    {
        return $this->karyas()->where('status', 'publish')->count();
    }

    public function wallet()
    {
        return $this->hasOne(\App\Models\Wallet::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
