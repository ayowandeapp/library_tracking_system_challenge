<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'loaned_at',
        'returned_at',
        'due_at'
    ];

    protected $casts = [
        'loaned_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public static function booted()
    {
        static::creating(function ($loan) {
            $loan->loaned_at = $loan->loaned_at ?? now();
            $loan->due_at = Carbon::parse($loan->loaned_at)->addDays(14);


        });
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
