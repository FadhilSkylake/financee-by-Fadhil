<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'budget'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
