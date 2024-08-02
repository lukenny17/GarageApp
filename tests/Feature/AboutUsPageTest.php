<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AboutUsPageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_the_about_us_page_correctly()
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('About Us');
        $response->assertSee('Welcome to ' . config('app.name'));
    }
}
