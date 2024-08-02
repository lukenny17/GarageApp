<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ServicesPageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_the_services_page_correctly()
    {
        // Manually insert test data
        $this->createTestServices();

        $response = $this->get('/services');

        $response->assertStatus(200);
        $response->assertViewIs('services');
        $response->assertSee('Our Services');
    }

    #[Test]
    public function it_displays_all_services_on_the_services_page()
    {
        // Manually insert test data
        $services = $this->createTestServices();

        $response = $this->get('/services');

        $response->assertStatus(200);

        foreach ($services as $service) {
            $response->assertSee($service->serviceName);
            $response->assertSee($service->description);
        }
    }

    /**
     * Helper function to create test services.
     *
     * @return \Illuminate\Support\Collection
     */
    private function createTestServices()
    {
        return collect([
            Service::create([
                'serviceName' => 'Full MOT',
                'description' => 'Comprehensive vehicle inspection.',
                'cost' => 100.00,
                'duration' => 3.0,
            ]),
            Service::create([
                'serviceName' => 'Engine Diagnostics',
                'description' => 'Identify and resolve engine issues.',
                'cost' => 150.00,
                'duration' => 2.0,
            ]),
            Service::create([
                'serviceName' => 'Oil Change',
                'description' => 'Change engine oil and filter.',
                'cost' => 50.00,
                'duration' => 1.0,
            ]),
        ]);
    }
}
