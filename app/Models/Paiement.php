<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    protected $fillable = [
        'user_id',
        'forfait_id',
        'hotspot_id',
        'reference',
        'montant',
        'statut',
        'methode',
        'response_api',
    ];

    protected $casts = [
        'response_api' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function forfait(): BelongsTo
    {
        return $this->belongsTo(Forfait::class);
    }

    public function hotspot(): BelongsTo
    {
        return $this->belongsTo(Hotspot::class);
    }
}