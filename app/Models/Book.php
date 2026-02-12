<?php

namespace App\Models;

use App\Enums\BookStatus;
use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'author_id',
        'publisher_id',
        'category_id'
    ];

    protected $casts = [
        'status' => BookStatus::class,
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function reservedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reserved_by');
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function reservation(): HasMany
    {
        return $this->hasMany(Reservation::class, 'book_id');
    }

    public function activeReservations(): HasMany
    {
        return $this->hasMany(Reservation::class)->where(function ($query) {
            $query->where('status', ReservationStatus::RESERVED->value)
                ->orWhere('status', ReservationStatus::ISSUED->value);
        });

    }
}
