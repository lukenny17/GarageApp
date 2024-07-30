<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CustomerProfileUpdateTest extends TestCase
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

    public function test_customer_can_update_email()
    {
        $user = $this->createUser('testuser@example.com');
        $this->actingAs($user);

        $response = $this->post(route('customer.updateEmail'), [
            'email' => 'newemail@example.com',
        ]);

        $response->assertRedirect(route('customer.settings'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'newemail@example.com',
        ]);
    }

    public function test_customer_can_update_phone_number()
    {
        $user = $this->createUser('testuser2@example.com');
        $this->actingAs($user);

        $response = $this->post(route('customer.updatePhone'), [
            'phone' => '1234567890',
        ]);

        $response->assertRedirect(route('customer.settings'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'phone' => '1234567890',
        ]);
    }

    public function test_customer_can_update_password()
    {
        $user = $this->createUser('testuser3@example.com');
        $this->actingAs($user);

        $response = $this->post(route('customer.updatePassword'), [
            'current_password' => 'password',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertRedirect(route('customer.settings'));
        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    }

    public function test_customer_can_delete_account()
    {
        $user = $this->createUser('testuser4@example.com');
        $this->actingAs($user);

        $response = $this->post(route('customer.destroyAccount'), [
            'password' => 'password',
        ]);

        $response->assertRedirect(route('welcome'));

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_correct_password_must_be_provided_to_delete_account()
    {
        $user = $this->createUser('testuser5@example.com');
        $this->actingAs($user);

        $response = $this->post(route('customer.destroyAccount'), [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }
}
