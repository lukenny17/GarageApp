<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Defines a one-to-one relationship between two models
    // 'this' refers to current instance of the User model
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function administrator(): HasOne
    {
        return $this->hasOne(Administrator::class);
    }

    public function customer(): HasOne
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

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
