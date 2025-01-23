<?php

// app/Models/TransactionCategory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionCategory extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id');
    }
}
