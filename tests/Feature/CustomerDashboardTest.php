<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Service;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CustomerDashboardTest extends TestCase
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
            'description' => 'Standard oil change',
        ]);
    }

    public function test_dashboard_displays_bookings_correctly()
    {
        $user = $this->createUser('customer@example.com');
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
        $response->assertSee('Bookings');
        $response->assertSee('Oil Change');
        $response->assertSee('Toyota Corolla');
        $response->assertSee('ABC123');
        $response->assertSee('scheduled');
    }

    public function test_dashboard_shows_no_bookings_message()
    {
        $user = $this->createUser('customer3@example.com');
        $this->actingAs($user);

        $response = $this->get(route('customer.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('has no bookings.');
        $response->assertSee('Bookings'); // Ensures that the section is rendered even if empty.
    }

    public function test_dashboard_shows_no_vehicles_message()
    {
        $user = $this->createUser('customer4@example.com');
        $this->actingAs($user);

        $response = $this->get(route('customer.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('has no vehicles registered with this account.');
        $response->assertSee('Vehicles'); // Ensures that the section is rendered even if empty.
    }

    public function test_dashboard_displays_settings_link()
    {
        $user = $this->createUser('customer5@example.com');
        $this->actingAs($user);

        $response = $this->get(route('customer.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Edit Profile');
        $response->assertSee(route('customer.settings'));
    }
}
