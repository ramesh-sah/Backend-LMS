<?php

namespace Database\Seeders;

use App\Http\Controllers\Api\Login\Login;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = DB::table('employees')->where('first_name', 'Santosh')->where('last_name', 'Tamang')->first();

        Login::create([
            'roll_number' => '22016112670',
            'email' => 'santosh.tamang@patancollege.edu.np',
            'role' => 'admin',
            'password' => Hash::make('1468santosh'),
            'employee_id' => $employee->employee_id
        ]);
    }
}
