<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'employee_id',
        'vehicle_id',
        'startTime',
        'duration',
        'status',
        'cost',
        'review_submitted',
    ];

    // name of method is name of table that 'Booking' has a relationship with.
    // 'belongsTo' what? e.g., 'User' class. Also pass in 'foreign key' and 'local key'

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_service');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function pendingServices()
    {
        return $this->hasMany(PendingBookingService::class);
    }
}
