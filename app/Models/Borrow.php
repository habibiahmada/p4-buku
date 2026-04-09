<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $table = 'borrowings';

    protected $fillable = [
        'user_id',
        'borrowed_date',
        'due_date',
        'charge',
        'returned_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function borrowDetails()
    {
        return $this->hasMany(BorrowDetail::class, 'borrowing_id');
    }
}
