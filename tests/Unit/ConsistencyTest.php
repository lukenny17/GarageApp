<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ConsistencyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test consistency in database transactions.
     * Ensures all transactions (creating a customer, vehicle, service, booking) either fully success together or fail together.
     */
    public function test_check_database_consistency(): void
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

            $service = Service::create([
                'serviceName' => 'Oil Change',
                'description' => 'Full synthetic oil change',
                'cost' => 50.00,
                'duration' => 1.0,
            ]);

            $booking = Booking::create([
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'startTime' => now(),
                'status' => 'scheduled',
                'duration' => 1.5,
                'cost' => 75.00,
            ]);

            // Attach the service to the booking
            $booking->services()->attach([$service->id]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->fail('Transaction failed, so consistency cannot be tested. Error: ' . $e->getMessage());
        }

        // Assert that the booking and service were created successfully
        $this->assertDatabaseHas('bookings', [
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'scheduled',
        ]);

        $this->assertDatabaseHas('booking_service', [
            'booking_id' => $booking->id,
            'service_id' => $service->id,
        ]);
    }
}
