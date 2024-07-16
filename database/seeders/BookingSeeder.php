<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookings = [
            [
                'customer_id' => Customer::find(2)->id,
                'employee_id' => null,
                'service_id' => 1,
                'vehicle_id' => 1,
                'startTime' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
                'duration' => 2.0,
                'status' => 'scheduled',
            ],
            [
                'customer_id' => Customer::find(2)->id,
                'employee_id' => null,
                'service_id' => 2,
                'vehicle_id' => 3,
                'startTime' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
                'duration' => 0.5,
                'status' => 'scheduled',
            ],
            [
                'customer_id' => Customer::find(2)->id,
                'employee_id' => null,
                'service_id' => 3,
                'vehicle_id' => 5,
                'startTime' => Carbon::now()->addDays(3)->format('Y-m-d H:i:s'),
                'duration' => 0.5,
                'status' => 'scheduled',
            ],
            [
                'customer_id' => Customer::find(2)->id,
                'employee_id' => null,
                'service_id' => 4,
                'vehicle_id' => 7,
                'startTime' => Carbon::now()->addDays(4)->format('Y-m-d H:i:s'),
                'duration' => 1.0,
                'status' => 'scheduled',
            ],
            [
                'customer_id' => Customer::find(2)->id,
                'employee_id' => null,
                'service_id' => 5,
                'vehicle_id' => 9,
                'startTime' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s'),
                'duration' => 0.5,
                'status' => 'scheduled',
            ],
            [
                'customer_id' => Customer::find(2)->id,
                'employee_id' => null,
                'service_id' => 6,
                'vehicle_id' => 11,
                'startTime' => Carbon::now()->addDays(6)->format('Y-m-d H:i:s'),
                'duration' => 0.5,
                'status' => 'scheduled',
            ],
            [
                'customer_id' => Customer::find(3)->id,
                'employee_id' => null,
                'service_id' => 7,
                'vehicle_id' => 13,
                'startTime' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
                'duration' => 1.0,
                'status' => 'scheduled',
            ],
            [
                'customer_id' => Customer::find(3)->id,
                'employee_id' => null,
                'service_id' => 8,
                'vehicle_id' => 15,
                'startTime' => Carbon::now()->addDays(8)->format('Y-m-d H:i:s'),
                'duration' => 1.5,
                'status' => 'scheduled',
            ],
        ];

        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}
