<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedGoogleId extends Model
{
    public $timestamps = false;

    protected $fillable = ['google_id'];

    public static function isBanned(string $googleId): bool
    {
        return static::where('google_id', $googleId)->exists();
    }
}
