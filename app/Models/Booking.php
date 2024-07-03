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
        'service_id',
        'vehicle_id',
        'startTime',
        'duration',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
