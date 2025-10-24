<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'published_at',
        'genre',
        'copies',
        'description',
        'published_at'
    ];

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    protected static function booted()
    {
        static::created(function (Book $book) {
            $book->inventory()->create([
                'total_copies' => $book->copies,
                'available_copies' => $book->copies,
            ]);
        });
    }
}
