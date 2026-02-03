<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        // 'years',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'author_id');
    }
}
