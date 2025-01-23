<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'amount',
        'due_date',
        'is_completed'
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'is_completed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
