<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'book_id',
        'status',
        'reserved_by',
        'reserved_at',
        'expires_at',
        'issued_at',
        'accepted_at',
    ];

    protected $casts = [
        'status' => ReservationStatus::class,
        'reserved_at' => 'datetime',
        'expires_at' => 'datetime',
        'issued_at' => 'datetime',
        'accepted_at' => 'datetime'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function reservedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reserved_by');
    }
}
