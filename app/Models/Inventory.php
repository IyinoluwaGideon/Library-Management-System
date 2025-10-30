<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Inventory extends Model
{
    use HasUuids;

    protected $table = 'inventory';

    protected $fillable = [
        'book_id',
        'total_copies',
        'available_copies',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function getAvailableAttribute(): bool
    {
        return  $this->available_copies > 0;
    }

    protected $hidden = ['created_at', 'updated_at'];
}
