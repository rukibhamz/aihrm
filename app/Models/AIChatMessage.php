<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIChatMessage extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'response',
        'tokens_used',
    ];

    protected $casts = [
        'tokens_used' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
