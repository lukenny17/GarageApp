<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Service;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class EmployeeDashboardTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    private function createUser($email, $role, $verified = true)
    {
        return User::create([
            'name' => 'Test User',
            'email' => $email,
            'password' => bcrypt('password'),
            'role' => $role,
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

    private function createService($serviceName)
    {
        return Service::create([
            'serviceName' => $serviceName,
            'cost' => 29.99,
            'duration' => 30,
            'description' => 'Standard ' . strtolower($serviceName),
        ]);
    }

    private function createBooking($customer, $vehicle, $services)
    {
        $booking = Booking::create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'startTime' => now()->addDays(1),
            'duration' => 60, // Assuming a cumulative or typical value
            'status' => 'scheduled',
            'cost' => 100.00, // Assuming a cumulative cost for simplicity
        ]);

        $serviceIds = array_map(function ($service) {
            return is_object($service) ? $service->id : $service;
        }, $services);

        $booking->services()->attach($serviceIds);

        return $booking;
    }
  
    public function test_employee_can_update_booking_status()
    {
        $employee = $this->createUser('employee@example.com', 'employee');
        $this->actingAs($employee);

        $customer = $this->createUser('customer2@example.com', 'customer');
        $vehicle = $this->createVehicle($customer);
        $service = $this->createService('Engine Diagnostics');

        $booking = $this->createBooking($customer, $vehicle, [$service]);

        $response = $this->post(route('employee.bookings.update-status', ['id' => $booking->id]), [
            'status' => 'completed',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'completed',
        ]);
    }

    public function test_edit_services_button_visibility()
    {
        $employee = $this->createUser('employee@example.com', 'employee');
        $this->actingAs($employee);

        $response = $this->get(route('employee.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Edit Services');
    }

    public function test_edit_services_functionality()
    {
        // Create an employee and log in
        $employeeUser = $this->createUser('employee@example.com', 'employee');
        $this->actingAs($employeeUser);

        $customer = $this->createUser('customer@example.com', 'customer');
        $vehicle = $this->createVehicle($customer);
        $service1 = $this->createService('Oil Change');
        $service2 = $this->createService('Tire Rotation');

        // Create a booking with the initial services
        $booking = $this->createBooking($customer, $vehicle, [$service1, $service2]);

        // Change the services (simulate employee updating the services)
        $newService = $this->createService('Brake Inspection');

        $response = $this->post(route('employee.bookings.updateServices', $booking->id), [
            'service_ids' => [$newService->id],
        ]);

        $response->assertStatus(200);

        // Verify that the pending service is in the pending_booking_service table
        $this->assertDatabaseHas('pending_booking_service', [
            'booking_id' => $booking->id,
            'service_id' => $newService->id,
        ]);

        // Verify that the old services are not present in the pending table
        $this->assertDatabaseMissing('pending_booking_service', [
            'booking_id' => $booking->id,
            'service_id' => $service1->id,
        ]);

        // Verify that the new service is not immediately added to the booking_service
        $this->assertDatabaseMissing('booking_service', [
            'booking_id' => $booking->id,
            'service_id' => $newService->id,
        ]);
    }
}
