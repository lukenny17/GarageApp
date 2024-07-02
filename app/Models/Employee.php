<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    // Completes the one-to-one ('HasOne') relationship identified in the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }    
}
