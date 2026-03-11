<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reservation_id',
        'amount',
        'paid_amount',
        'status',
        'method',
        'reference_number',
        'paid_at',
        'notes',
        'card_number',
        'mobile_number',
    ];

    /**
     * Cast attributes to appropriate types.
     *
     * We cast `paid_at` as a datetime so that when the model is
     * hydrated Eloquent will return a Carbon instance.  Without this
     * cast the attribute is treated as a plain string, and using
     * `optional($p->paid_at)->format(...)` silently returns nothing
     * which is why the payment listing showed an empty column even
     * though the header was present.
     */
    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function reservation()
    {
        // always include soft-deleted reservations so that old payments still
        // reference their reservation even if the reservation has been trashed.
        // this prevents null-reservation errors when displaying payment lists.
        return $this->belongsTo(Reservation::class)->withTrashed();
    }
}
