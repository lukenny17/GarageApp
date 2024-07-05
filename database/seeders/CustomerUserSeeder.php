<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
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
            ['name' => 'Customer', 'email' => 'customer@example.com'],
            ['name' => 'Luke', 'email' => 'luke@example.com'],
            ['name' => 'Joel', 'email' => 'joel@example.com'],
            ['name' => 'Alice', 'email' => 'alice@example.com'],
            ['name' => 'Bob', 'email' => 'bob@example.com'],
            ['name' => 'Carol', 'email' => 'carol@example.com'],
            ['name' => 'Paul', 'email' => 'paul@example.com'],
            ['name' => 'Eve', 'email' => 'eve@example.com'],
            ['name' => 'Frank', 'email' => 'frank@example.com'],
            ['name' => 'Grace', 'email' => 'grace@example.com'],
        ];

        // Loop through each customer data and create the user and customer record
        foreach ($customers as $customerData) {
            $user = User::create([
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]);

            // Create related customer record
            Customer::create(['user_id' => $user->id]);
        }
    }
}
