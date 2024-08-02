<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\User;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Administrator;
use Illuminate\Support\Facades\Hash;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    private function createUser($role, $email, $name = 'Test User')
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => $role,
        ]);

        switch ($role) {
            case 'customer':
                Customer::create(['user_id' => $user->id]);
                break;
            case 'employee':
                Employee::create(['user_id' => $user->id]);
                break;
            case 'admin':
                Administrator::create(['user_id' => $user->id]);
                break;
        }

        return $user;
    }

    private function createVehicle($user)
    {
        return \App\Models\Vehicle::create([
            'user_id' => $user->id,
            'make' => 'Toyota',
            'model' => 'Corolla',
            'licensePlate' => 'XYZ123',
            'year' => 2020,
        ]);
    }

    private function createBooking($customer, $vehicle, $employee = null)
    {
        return Booking::create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'employee_id' => $employee ? $employee->id : null,
            'startTime' => now()->addDay(),
            'duration' => 2,
            'status' => 'scheduled',
            'cost' => 100.00,
        ]);
    }

    public function test_admin_can_view_dashboard()
    {
        $admin = $this->createUser('admin', 'admin@example.com');
        $this->actingAs($admin);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
    }

    public function test_admin_can_create_user()
    {
        $admin = $this->createUser('admin', 'admin@example.com');
        $this->actingAs($admin);

        $response = $this->post(route('admin.createUser'), [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'customer',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success', 'User created successfully.');

        $this->assertDatabaseHas('users', [
            'email' => 'customer@example.com',
            'role' => 'customer',
        ]);

        $this->assertDatabaseHas('customers', [
            'user_id' => User::where('email', 'customer@example.com')->first()->id,
        ]);
    }

    public function test_admin_can_get_bookings()
    {
        $admin = $this->createUser('admin', 'admin@example.com');
        $this->actingAs($admin);

        $customer = $this->createUser('customer', 'customer@example.com');
        $vehicle = $this->createVehicle($customer);
        $employee = $this->createUser('employee', 'employee@example.com');
        $booking = $this->createBooking($customer, $vehicle, $employee);

        $response = $this->postJson(route('admin.getBookings'), [
            'start_date' => now()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['bookings', 'employees']);
        $response->assertJsonFragment([
            'id' => $booking->id,
            'status' => 'scheduled',
        ]);
    }

    public function test_admin_can_assign_employee_to_booking()
    {
        $admin = $this->createUser('admin', 'admin@example.com');
        $this->actingAs($admin);

        $employee = $this->createUser('employee', 'newemployee@example.com');
        $customer = $this->createUser('customer', 'customer@example.com');
        $vehicle = $this->createVehicle($customer);
        $booking = $this->createBooking($customer, $vehicle);

        $response = $this->post(route('admin.assignEmployee'), [
            'booking_id' => $booking->id,
            'employee_id' => $employee->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'employee_id' => $employee->id,
        ]);
    }

    public function test_admin_can_create_service()
    {
        $admin = $this->createUser('admin', 'admin@example.com');
        $this->actingAs($admin);

        $response = $this->post(route('admin.storeService'), [
            'serviceName' => 'Wheel Alignment',
            'description' => 'Adjust the angles of wheels to manufacturer specifications',
            'cost' => 49.99,
            'duration' => 1.5,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success', 'Service added successfully.');

        $this->assertDatabaseHas('services', [
            'serviceName' => 'Wheel Alignment',
            'description' => 'Adjust the angles of wheels to manufacturer specifications',
            'cost' => 49.99,
            'duration' => 1.5,
        ]);
    }
}
