<?php

namespace Database\Seeders;

use App\Http\Controllers\Api\UserRegistration\EmployeeRegistration\EmployeeRegistration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MAnagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeRegistration::create([
            'first_name' => 'Santosh',
            'last_name' => 'Tamang',
            'dob' => '2059-10-11',
            'address' => 'Kathmandu',
            'contact_number' => '9869053366',
            'enrollment_year' => '2022',
            'account_creation_date' => '2022-10-11',
            'account_status' => 'active',
            'gender' => 'male'
        ]);
    }
}
