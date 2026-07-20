<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forfait extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prix',
        'duree',
        'download_speed',
        'upload_speed',
        'mikrotik_profile',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'prix' => 'decimal:2',
    ];

    public function hotspots()
    {
        return $this->belongsToMany(Hotspot::class, 'hotspot_forfait');
    }
}