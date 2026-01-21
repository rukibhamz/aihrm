<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIRequest extends Model
{
    protected $table = 'ai_requests';

    protected $fillable = [
        'user_id',
        'feature',
        'tokens_used',
        'cost',
        'request_summary',
    ];

    protected $casts = [
        'tokens_used' => 'integer',
        'cost' => 'decimal:4',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
