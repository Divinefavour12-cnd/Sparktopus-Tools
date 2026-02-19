<?php

namespace App\Models;

use App\Traits\ClearCache;
use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Shetabit\Visitor\Traits\Visitor;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Notifications\AppNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Overtrue\LaravelFavorite\Traits\Favoriter;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasRoles,
        SearchableTrait,
        Visitor,
        Favoriter,
        SoftDeletes,
        InteractsWithMedia,
        ClearCache;

    protected $appends = ['favorite_tools', 'is_ads_allowed'];

    protected $fillable = [
        'name', 'email', 'password', 'about', 'username', 'status',
        'provider', 'provider_id', 'email_verified_at', 'google2fa_secret', 'picture'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot(): void
    {
        parent::boot();
        self::bootClearsResponseCache();
    }

    /* =========================
       RELATIONSHIPS
    ========================== */

    public function pages()
    {
        return $this->hasMany(Page::class, 'author_id', 'id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id')
            ->orderBy('transactions.created_at', 'desc');
    }

    /* =========================
       SUBSCRIPTIONS
    ========================== */

    public function activeSubscriptions()
    {
        return $this->transactions()
            ->active()
            ->plan()
            ->whereDate('expiry_date', '>', now());
    }

    public function getActiveSubscription()
    {
        return $this->activeSubscriptions()->first();
    }

    public function hasActiveSubscription()
    {
        return $this->activeSubscriptions()->exists();
    }

    public function subscription(): Attribute
    {
        return new Attribute(
            get: fn () => $this->getActiveSubscription(),
        );
    }

    /* =========================
       PLAN & ACCESS LOGIC (NEW)
    ========================== */

    public function planLevel(): string
    {
        $subscription = $this->getActiveSubscription();

        if (!$subscription) {
            return 'free';
        }

        return match ((int) $subscription->plan_id) {
            1 => 'free',
            2 => 'classic',
            3 => 'plus',
            4 => 'pro',
            default => 'free',
        };
    }

    public function planRank(): int
    {
        return match ($this->planLevel()) {
            'free' => 0,
            'classic' => 1,
            'plus' => 2,
            'pro' => 3,
            default => 0,
        };
    }

    public function canAccessTool(string $requiredLevel): bool
    {
        $levels = [
            'free' => 0,
            'classic' => 1,
            'plus' => 2,
            'pro' => 3,
        ];

        return $this->planRank() >= ($levels[$requiredLevel] ?? 0);
    }

    public function shouldSeeAds(): bool
    {
        return in_array($this->planLevel(), ['free', 'classic']);
    }

    /**
     * Appended attribute for Blade templates.
     * Uses shouldSeeAds() as the single source of truth.
     */
    protected function isAdsAllowed(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->shouldSeeAds(),
        );
    }

    public function toolUsages()
    {
        return $this->hasMany(ToolUsage::class);
    }

    public function canUseTool(string $toolSlug): bool
    {
        $currentPlan = $this->planLevel();
        
        $limit = $this->getDailyLimit($toolSlug);

        $usage = $this->toolUsages()->firstOrCreate(
            ['tool_name' => $toolSlug],
            [
                'usage_count' => 0,
                'plan'        => $currentPlan,
                'last_used_at'=> now(),
            ]
        );

        // Reset if plan changed
        if ($usage->plan !== $currentPlan) {
            $usage->update([
                'usage_count' => 0,
                'plan'        => $currentPlan,
                'last_used_at'=> now(),
            ]);
            $usage = $usage->fresh();
        }

        // Hourly reset
        if ($usage->last_used_at && \Carbon\Carbon::parse($usage->last_used_at)->diffInMinutes(now()) >= 60) {
            $usage->update([
                'usage_count' => 0,
                'last_used_at'=> now(),
            ]);
            $usage = $usage->fresh();
        }

        return $usage->usage_count < $limit;
    }

    public function incrementToolUsage(string $toolSlug): void
    {
        $usage = $this->toolUsages()->where('tool_name', $toolSlug)->first();
        if ($usage) {
            $usage->increment('usage_count');
            $usage->update(['last_used_at' => now()]);

            // Check if limit reached to notify
            $limit = $this->getDailyLimit($toolSlug);
            if ($usage->usage_count == $limit) {
                $this->notify(new AppNotification(
                    __('Usage Limit Reached'),
                    __('You have reached your limit for :tool. It will reset soon.', ['tool' => str_replace('-', ' ', ucwords($toolSlug, '-'))]),
                    'warning'
                ));
            }
        }
    }

    public function getDailyLimit(string $toolSlug): int
    {
        $limits = [
            'free'    => 3,
            'classic' => 20,
            'plus'    => 50,
            'pro'     => 200,
        ];

        return $limits[$this->planLevel()] ?? 3;
    }

    public function getRemainingUsage(string $toolSlug): int
    {
        $limit = $this->getDailyLimit($toolSlug);
        $usage = $this->toolUsages()->where('tool_name', $toolSlug)->first();
        
        return $usage ? max($limit - $usage->usage_count, 0) : $limit;
    }

    public function getToolResetTime(string $toolSlug): string
    {
        $usage = $this->toolUsages()->where('tool_name', $toolSlug)->first();
        
        if (!$usage || !$usage->last_used_at) {
            return '';
        }
        
        // Reset happens 60 minutes after last usage
        return \Carbon\Carbon::parse($usage->last_used_at)->addMinutes(60)->format('g:i A');
    }

    /* =========================
       OTHER FEATURES
    ========================== */

    public function favoriteTools(): Attribute
    {
        return new Attribute(
            get: fn () => $this->getFavoriteItems(Tool::class)
                ->with('translations')
                ->get(),
        );
    }

    public function sendPasswordResetNotification($token)
    {
        $notification = new ResetPassword($token);

        $notification->createUrlUsing(function ($notifiable, $token) {
            return url(route("password.reset", [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]));
        });

        $this->notify($notification);
    }

    public function hasVerifiedEmail()
    {
        if (setting('activation_required', 0) == 0) {
            return true;
        }

        return !empty($this->email_verified_at);
    }

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn ($_, $attr) => Arr::first(explode(' ', $attr['name'])),
        );
    }

    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn ($_, $attr) => Arr::last(explode(' ', $attr['name'])),
        );
    }
}