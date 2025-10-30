<?php

namespace App\Models;

use App\Policies\BorrowPolicy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Borrow extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'book_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'fine',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    protected $hidden = ['created_at', 'updated_at'];
}
