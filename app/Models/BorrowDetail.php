<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowDetail extends Model
{
    protected $table = 'borrowings_detail';

    protected $fillable = [
        'borrowing_id',
        'book_id',
        'qty',
    ];

    public function borrow()
    {
        return $this->belongsTo(Borrow::class, 'borrowing_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
