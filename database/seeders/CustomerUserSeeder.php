<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array of customers data
        $customers = [
            ['name' => 'Customer', 'email' => 'customer@example.com', 'phone' => '07581089364'],
            ['name' => 'Luke', 'email' => 'luke@example.com', 'phone' => '54545114452'],
            ['name' => 'Joel', 'email' => 'joel@example.com', 'phone' => '58489936242'],
            ['name' => 'Alice', 'email' => 'alice@example.com', 'phone' => '6988742123'],
            ['name' => 'Bob', 'email' => 'bob@example.com', 'phone' => '07844458266'],
            ['name' => 'Carol', 'email' => 'carol@example.com', 'phone' => '01303254613'],
            ['name' => 'Paul', 'email' => 'paul@example.com', 'phone' => '031312999999'],
            ['name' => 'Eve', 'email' => 'eve@example.com', 'phone' => '0781142568663'],
            ['name' => 'Frank', 'email' => 'frank@example.com', 'phone' => '08799412333'],
            ['name' => 'Grace', 'email' => 'grace@example.com', 'phone' => '07845155881'],
        ];

        // Loop through each customer data and create the user and customer record
        foreach ($customers as $customerData) {
            $user = User::create([
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'phone' => $customerData['phone'],
                'password' => Hash::make('password'),
                'role' => 'customer',
                'email_verified_at' => Carbon::now(),
            ]);

            // Create related customer record
            Customer::create(['user_id' => $user->id]);
        }
    }
}
