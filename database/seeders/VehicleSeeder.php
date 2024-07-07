<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            ['user_id' => 1, 'make' => 'Toyota', 'model' => 'Camry', 'year' => 2020, 'licensePlate' => 'ABC123'],
            ['user_id' => 1, 'make' => 'Honda', 'model' => 'Civic', 'year' => 2018, 'licensePlate' => 'XYZ456'],
            ['user_id' => 2, 'make' => 'Ford', 'model' => 'Focus', 'year' => 2019, 'licensePlate' => 'LMN789'],
            ['user_id' => 2, 'make' => 'Chevrolet', 'model' => 'Malibu', 'year' => 2017, 'licensePlate' => 'QWE123'],
            ['user_id' => 3, 'make' => 'Nissan', 'model' => 'Altima', 'year' => 2021, 'licensePlate' => 'RTY456'],
            ['user_id' => 3, 'make' => 'BMW', 'model' => 'X5', 'year' => 2022, 'licensePlate' => 'UIO789'],
            ['user_id' => 4, 'make' => 'Mercedes', 'model' => 'C-Class', 'year' => 2020, 'licensePlate' => 'PAS234'],
            ['user_id' => 4, 'make' => 'Audi', 'model' => 'A4', 'year' => 2019, 'licensePlate' => 'DFG456'],
            ['user_id' => 5, 'make' => 'Volkswagen', 'model' => 'Passat', 'year' => 2018, 'licensePlate' => 'GHJ789'],
            ['user_id' => 5, 'make' => 'Tesla', 'model' => 'Model S', 'year' => 2021, 'licensePlate' => 'JKL012'],
            ['user_id' => 6, 'make' => 'Hyundai', 'model' => 'Elantra', 'year' => 2017, 'licensePlate' => 'ZXC347'],
            ['user_id' => 6, 'make' => 'Kia', 'model' => 'Sorento', 'year' => 2020, 'licensePlate' => 'VBN678'],
            ['user_id' => 7, 'make' => 'Mazda', 'model' => 'CX-5', 'year' => 2019, 'licensePlate' => 'QWE124'],
            ['user_id' => 7, 'make' => 'Subaru', 'model' => 'Outback', 'year' => 2018, 'licensePlate' => 'ASD456'],
            ['user_id' => 8, 'make' => 'Lexus', 'model' => 'RX', 'year' => 2021, 'licensePlate' => 'DFG789'],
            ['user_id' => 8, 'make' => 'Infiniti', 'model' => 'Q50', 'year' => 2020, 'licensePlate' => 'HJK012'],
            ['user_id' => 9, 'make' => 'Acura', 'model' => 'TLX', 'year' => 2019, 'licensePlate' => 'ZXC345'],
            ['user_id' => 9, 'make' => 'Cadillac', 'model' => 'XT5', 'year' => 2018, 'licensePlate' => 'VBM678'],
            ['user_id' => 10, 'make' => 'Lincoln', 'model' => 'MKZ', 'year' => 2020, 'licensePlate' => 'RTY123'],
            ['user_id' => 10, 'make' => 'Jaguar', 'model' => 'F-Pace', 'year' => 2021, 'licensePlate' => 'UIO456'],
        ];

        // Loop through each vehicle data and create the vehicle record
        foreach ($vehicles as $vehicleData) {
            Vehicle::create($vehicleData);
        }
    }
}
