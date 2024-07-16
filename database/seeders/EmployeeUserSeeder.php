<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { {
            // Array of customers data
            $employees = [
                ['name' => 'Employee', 'email' => 'employee@example.com'],
                ['name' => 'Julie', 'email' => 'julie@example.com'],
                ['name' => 'Keith', 'email' => 'keith@example.com'],
                ['name' => 'Pamela', 'email' => 'pamela@example.com'],
                ['name' => 'Harry', 'email' => 'harry@example.com'],
                ['name' => 'Emma', 'email' => 'emma@example.com'],
            ];

            // Loop through each employee data and create the user and employee record
            foreach ($employees as $employeeData) {
                $user = User::create([
                    'name' => $employeeData['name'],
                    'email' => $employeeData['email'],
                    'password' => Hash::make('password'),
                    'role' => 'employee',
                    'email_verified_at' => Carbon::now(),
                ]);

                // Create related employee record
                Employee::create(['user_id' => $user->id]);
            }
        }
    }
}
