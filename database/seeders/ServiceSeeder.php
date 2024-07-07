<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'serviceName' => 'Full Service',
                'description' => 'Complete vehicle inspection, covering all major systems and components. This service ensures your vehicle is running smoothly and efficiently, addressing any potential issues before they become major problems.',
                'cost' => 139.99,
                'duration' => 2.0
            ],
            [
                'serviceName' => 'Oil Change',
                'description' => 'Regular and synthetic oil change options to keep your engine running smoothly. We also include a multi-point inspection to ensure everything else is in top condition.',
                'cost' => 39.99,
                'duration' => 0.5
            ],
            [
                'serviceName' => 'Tyre Change',
                'description' => 'Mounting, balancing, and rotation of tyres for optimal performance and safety. We ensure your tyres are properly aligned and inflated to manufacturer specifications.',
                'cost' => 29.99,
                'duration' => 0.5
            ],
            [
                'serviceName' => 'Brake Service',
                'description' => 'Comprehensive brake service including pads, discs, and inspection. Our technicians ensure your braking system is functioning perfectly to keep you safe on the road.',
                'cost' => 99.99,
                'duration' => 1.0
            ],
            [
                'serviceName' => 'Battery Replacement',
                'description' => 'Testing and replacement service for your vehicle’s battery. We check your current battery’s health and replace it with a high-quality new one if necessary.',
                'cost' => 69.99,
                'duration' => 0.5
            ],
            [
                'serviceName' => 'Air Conditioning',
                'description' => 'Check and recharge your AC unit to keep you cool in hot weather. We also inspect the entire system for leaks or damage to ensure maximum efficiency.',
                'cost' => 59.99,
                'duration' => 0.5
            ],
            [
                'serviceName' => 'Transmission Fluid Flush',
                'description' => 'Complete transmission fluid replacement to maintain smooth gear shifts and extend transmission life. We use high-quality fluids that meet or exceed manufacturer standards.',
                'cost' => 89.99,
                'duration' => 1.0
            ],
            [
                'serviceName' => 'Engine Diagnostics',
                'description' => 'Advanced troubleshooting using the latest diagnostic tools to pinpoint engine issues. Our experts provide detailed reports and recommendations for any necessary repairs.',
                'cost' => 49.99,
                'duration' => 1.5
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
