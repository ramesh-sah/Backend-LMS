<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublishersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        foreach(range(0,10) as $value){
            DB::table('publishers')->insert([
                'publisher_id'=> $faker->uuid,
                'publisher_name' => $faker->name,
                'publication_place' => $faker->city,
            ]);
        }
        
    }

}
