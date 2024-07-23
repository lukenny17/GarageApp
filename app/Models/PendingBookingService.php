<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingBookingService extends Model
{
    use HasFactory;
    
    protected $table = 'pending_booking_service';

    protected $fillable = [
        'booking_id',
        'service_id',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
