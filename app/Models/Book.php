<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'publication_year',
        'stock',
    ];

    public function borrowDetails()
    {
        return $this->hasMany(BorrowDetail::class);
    }
}
