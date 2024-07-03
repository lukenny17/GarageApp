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
                'description' => 'Complete vehicle inspection.',
                'cost' => 139.99,
                'duration' => 2.0
            ],
            [
                'serviceName' => 'Oil Change',
                'description' => 'Regular and synthetic options.',
                'cost' => 39.99,
                'duration' => 0.5
            ],
            [
                'serviceName' => 'Tyre Change',
                'description' => 'Mounting, balancing, and rotation.',
                'cost' => 29.99,
                'duration' => 0.5
            ],
            [
                'serviceName' => 'Brake Service',
                'description' => 'Pads, discs, and inspection.',
                'cost' => 99.99,
                'duration' => 1.0
            ],
            [
                'serviceName' => 'Battery Replacement',
                'description' => 'Testing and replacement service.',
                'cost' => 69.99,
                'duration' => 0.5
            ],
            [
                'serviceName' => 'Air Conditioning',
                'description' => 'Check and recharge AC unit.',
                'cost' => 59.99,
                'duration' => 0.5
            ],
            [
                'serviceName' => 'Transmission Fluid Flush',
                'description' => 'Transmission fluid replacement.',
                'cost' => 89.99,
                'duration' => 1.0
            ],
            [
                'serviceName' => 'Engine Diagnostics',
                'description' => 'Advanced troubleshooting.',
                'cost' => 49.99,
                'duration' => 1.5
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
