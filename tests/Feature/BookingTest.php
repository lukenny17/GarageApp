<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class BookingTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    private function createUser($email, $verified = true)
    {
        return User::create([
            'name' => 'Test User',
            'email' => $email,
            'password' => bcrypt('password'),
            'role' => 'customer',
            'email_verified_at' => $verified ? now() : null,
        ]);
    }

    private function createVehicle($user)
    {
        return Vehicle::create([
            'user_id' => $user->id,
            'make' => 'Toyota',
            'model' => 'Corolla',
            'licensePlate' => 'ABC123',
            'year' => 2021,
        ]);
    }

    private function createService()
    {
        return Service::create([
            'serviceName' => 'Oil Change',
            'cost' => 29.99,
            'duration' => 30,
            'description' => 'Standard oil change'
        ]);
    }

    public function test_customer_can_create_booking()
    {
        $user = $this->createUser('testuser1@example.com');
        $this->actingAs($user);

        $vehicle = $this->createVehicle($user);
        $service = $this->createService();

        $response = $this->post(route('bookings.store'), [
            'vehicle_id' => $vehicle->id,
            'service_ids' => [$service->id],
            'date' => now()->addDays(1)->toDateString(),
            'time' => '10:00',
            'status' => 'scheduled',
        ]);

        $response->assertStatus(302); // Assuming redirect after successful booking creation
        $this->assertDatabaseHas('bookings', [
            'customer_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'scheduled',
        ]);
    }

    public function test_customer_can_update_booking()
    {
        $user = $this->createUser('testuser2@example.com');
        $this->actingAs($user);

        $vehicle = $this->createVehicle($user);
        $service = $this->createService();

        $booking = Booking::create([
            'customer_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'startTime' => now()->addDays(1),
            'duration' => $service->duration,
            'status' => 'scheduled',
            'cost' => $service->cost,
        ]);

        $booking->services()->attach($service->id);

        $response = $this->post(route('customer.bookings.reschedule', $booking->id), [
            'date' => now()->addDays(2)->toDateString(),
            'time' => '12:00:00',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'startTime' => now()->addDays(2)->toDateString() . ' 12:00:00',
        ]);
    }

    public function test_customer_can_cancel_booking()
    {
        $user = $this->createUser('testuser3@example.com');
        $this->actingAs($user);

        $vehicle = $this->createVehicle($user);
        $service = $this->createService();

        $booking = Booking::create([
            'customer_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'startTime' => now()->addDays(1),
            'duration' => $service->duration,
            'status' => 'scheduled',
            'cost' => $service->cost,
        ]);

        $booking->services()->attach($service->id);

        $response = $this->post(route('customer.bookings.cancel', $booking->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    public function test_booking_shows_up_on_dashboard()
    {
        $user = $this->createUser('testuser4@example.com');
        $this->actingAs($user);

        $vehicle = $this->createVehicle($user);
        $service = $this->createService();

        $booking = Booking::create([
            'customer_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'startTime' => now()->addDays(1),
            'duration' => $service->duration,
            'status' => 'scheduled',
            'cost' => $service->cost,
        ]);

        $booking->services()->attach($service->id);

        $response = $this->get(route('customer.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Oil Change');
        $response->assertSee('Toyota');
        $response->assertSee('Corolla');
        $response->assertSee('scheduled');
    }
}
