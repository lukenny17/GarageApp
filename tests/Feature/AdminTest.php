<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function only_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertStatus(200);

        $this->actingAs($customer)
            ->get('/admin')
            ->assertStatus(403); // Check for 403 status code
    }
}
