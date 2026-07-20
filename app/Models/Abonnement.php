<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hotspot_id',
        'forfait_id',
        'date_debut',
        'date_fin',
        'statut',
        'reference_paiement',
        'notes',
        'hotspot_username',
        'hotspot_password',
        'sync_mikrotik',
        'rappel_envoye',
        'sync_tentatives',
        'dernier_essai_sync',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotspot()
    {
        return $this->belongsTo(Hotspot::class);
    }

    public function forfait()
    {
        return $this->belongsTo(Forfait::class);
    }

    public function isExpired(): bool
    {
        return $this->date_fin->isPast();
    }
}