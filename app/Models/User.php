<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function administrator()
    {
        return $this->hasOne(Administrator::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function bookingsAsCustomer()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function bookingsAsEmployee()
    {
        return $this->hasMany(Booking::class, 'employee_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
