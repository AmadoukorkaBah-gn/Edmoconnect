<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MikrotikServer extends Model
{
    protected $fillable = [
        'name',
        'host',
        'port',
        'username',
        'password',
        'ssl',
        'location',
        'is_active',
    ];

    protected $casts = [
        'ssl' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function hotspots(): HasMany
    {
        return $this->hasMany(Hotspot::class);
    }
}