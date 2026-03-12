<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_number',
        'type',
        'capacity',
        'price_per_night',
        'status',
        'description',
    ];

    
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /** Chambres libres */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Synchronise automatiquement le statut de la chambre
     * selon les réservations actives ou futures.
     *
     * Règles :
     *  - occupied   : réservation active aujourd'hui (checked_in ou confirmed dont check_in <= today < check_out)
     *  - reserved   : réservation future confirmée (check_in > today)
     *  - available  : aucune réservation active/future (sauf maintenance)
     *  - maintenance: jamais touché ici, géré manuellement
     */
    public function syncStatus(): void
    {
        // Ne jamais écraser une chambre en maintenance
        if ($this->status === 'maintenance') {
            return;
        }

        $today = now()->toDateString();

        // 1) Y a-t-il un check-in actif aujourd'hui ?
        $occupiedNow = $this->reservations()
            ->whereNotIn('status', ['cancelled', 'checked_out'])
            ->where('check_in',  '<=', $today)
            ->where('check_out', '>',  $today)
            ->exists();

        if ($occupiedNow) {
            $this->update(['status' => 'occupied']);
            return;
        }

        $futureReservation = $this->reservations()
            ->whereIn('status', ['confirmed', 'pending', 'paid', 'partial'])
            ->where('check_in', '>', $today)
            ->exists();

        if ($futureReservation) {
            $this->update(['status' => 'reserved']);
            return;
        }

        $this->update(['status' => 'available']);
    }
}