<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'category',
        'type',
        'transaction_date',
        'description'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'double:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
