<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotspot extends Model
{
    use HasFactory;

    protected $fillable = [
        'mikrotik_server_id',
        'name',
        'profile',
        'interface',
        'address',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function mikrotikServer()
    {
        return $this->belongsTo(MikrotikServer::class);
    }

    public function forfaits()
    {
        return $this->belongsToMany(Forfait::class, 'hotspot_forfait');
    }

    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }
}