<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    #[Test]
    public function it_displays_the_welcome_page_correctly()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
        $response->assertSee(config('app.name'));
        $response->assertSee('Rev Up Your Service.');
        $response->assertSee('Book Now');
    }

    #[Test]
    public function it_displays_the_services_section_on_welcome_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Our Services');
        $response->assertSee('Learn More');
    }

    #[Test]
    public function it_displays_the_testimonials_section_on_welcome_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Testimonials');
        $response->assertSee('Excellent service! My car has never run better.');
    }

    #[Test]
    public function it_displays_the_map_on_welcome_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('iframe');
    }
}
