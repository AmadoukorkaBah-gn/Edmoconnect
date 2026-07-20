<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    protected $table = 'parametres';

    protected $fillable = [
        'nom_entreprise',
        'telephone_support',
        'email_support',
        'adresse',
        'rappel_expiration_minutes',
    ];

    /**
     * Recupere la ligne unique de parametres, la cree si elle n'existe pas encore.
     */
    public static function courant(): self
    {
        return self::first() ?? self::create([]);
    }
}