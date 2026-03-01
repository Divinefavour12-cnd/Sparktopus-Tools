<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'message',
        'screenshot_path',
        'page_url',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The user who submitted this feedback (if logged in).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the screenshot URL for display.
     */
    public function getScreenshotUrlAttribute()
    {
        if ($this->screenshot_path) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->screenshot_path);
        }
        return null;
    }
}
