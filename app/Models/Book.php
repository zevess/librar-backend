<?php

namespace App\Models;

use App\Enums\BookStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        
        'status',
        'reserved_by'
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

    public function publishingHouse(): BelongsTo
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }
}
