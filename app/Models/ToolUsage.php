<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolUsage extends Model
{
    protected $table = 'tool_usages';

    protected $fillable = [
        'user_id',
        'tool_name',
        'plan',
        'usage_count',
        'last_used_at',
    ];
}