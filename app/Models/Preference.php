<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Preference extends Model
{
    protected $fillable = [
        'user_id',
        'preferencable_id',
        'preferencable_type',
    ];

    const TYPE_MAP = [
        'category' => 'App\Models\Category',
        'author' => 'App\Models\Author',
        'source' => 'App\Models\Source',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function preferencable(): MorphTo
    {
        return $this->morphTo();
    }
}
