<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'serviceName',
        'description',
        'cost',
        'duration',
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_service');
    }

    public function pendingBookings()
    {
        return $this->belongsToMany(Booking::class, 'pending_booking_service');
    }
}
