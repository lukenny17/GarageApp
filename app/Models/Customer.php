<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
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
    
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }    
}
