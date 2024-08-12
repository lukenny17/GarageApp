<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AtomicityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test atomicity in database transactions.
     * Attempt to create a booking with a duplicate ID. To be atomic, this should cause all transactions to rollback and no record of either transaction to exist in database.
     */
    public function test_check_database_atomicity(): void
    {
        DB::beginTransaction();
        try {
            // Create booking prerequisites
            $user = User::create([
                'name' => 'Test User',
                'email' => 'testuser@example.com',
                'password' => bcrypt('password'),
            ]);

            $customer = Customer::create([
                'user_id' => $user->id,
            ]);

            $vehicle = Vehicle::create([
                'user_id' => $customer->user_id,
                'make' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2020,
                'licensePlate' => 'XYZ 1234',
            ]);

            $service1 = Service::create([
                'serviceName' => 'Oil Change',
                'description' => 'Full synthetic oil change',
                'cost' => 50.00,
                'duration' => 1.0,
            ]);

            $service2 = Service::create([
                'serviceName' => 'Tire Rotation',
                'description' => 'Tire rotation and balance',
                'cost' => 30.00,
                'duration' => 0.5,
            ]);

            // Create the booking
            $booking = Booking::create([
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'startTime' => now(),
                'status' => 'scheduled',
            ]);

            $booking->services()->attach([$service1->id, $service2->id]); // Attaching multiple services

            // Introduce an error (e.g., duplicate entry)
            Booking::create([
                'id' => $booking->id, // Duplicate ID error
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'startTime' => now(),
                'status' => 'scheduled',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            // Ensure that no records exist with the specific identifiers
            $this->assertDatabaseMissing('users', ['name' => 'Test User']);
            $this->assertDatabaseMissing('vehicles', ['licensePlate' => 'XYZ 1234']);
            $this->assertDatabaseMissing('services', ['serviceName' => 'Oil Change']);
            $this->assertDatabaseMissing('services', ['serviceName' => 'Tire Rotation']);
            $this->assertDatabaseMissing('bookings', ['customer_id' => 1]);
        }
    }
}
