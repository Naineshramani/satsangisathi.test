<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public static function findOrCreateFor(string $type, int $targetId, int $validDays = 30): self
    {
        $existing = self::where('type', $type)
            ->where('target_id', $targetId)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();

        if ($existing) {
            return $existing;
        }

        do {
            $token = \Illuminate\Support\Str::random(8);
        } while (self::where('token', $token)->exists());

        $link = new self();
        $link->token = $token;
        $link->type = $type;
        $link->target_id = $targetId;
        $link->expires_at = now()->addDays($validDays);
        $link->save();

        return $link;
    }
}
