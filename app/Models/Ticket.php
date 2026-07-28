<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
   protected $fillable = [
    'hotspot_id',
    'user_id',
    'forfait_id',
    'code',
    'username',
    'password',
    'status',
    'activated_at',
    'used',
    'used_at',
    'batch',
];


protected $casts = [
    'activated_at' => 'datetime',
];

    public function hotspot()
    {
        return $this->belongsTo(Hotspot::class);
    }

    public function forfait()
    {
        return $this->belongsTo(Forfait::class);
    }
    public function user()
{
    return $this->belongsTo(User::class);
}
}