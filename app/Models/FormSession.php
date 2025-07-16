<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'current_step',
        'form_data',
        'last_saved_at'
    ];

    protected function casts(): array
    {
        return [
            'form_data' => 'array',
            'last_saved_at' => 'datetime',
        ];
    }

    /**
     * Scope for finding by session ID
     */
    public function scopeBySessionId(Builder $query, string $sessionId): Builder
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope for recent sessions
     */
    public function scopeRecent(Builder $query, int $hours = 24): Builder
    {
        return $query->where('last_saved_at', '>=', now()->subHours($hours));
    }
}
