<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingPropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 30; $i++) {

            $random_number = rand(3, 10);

            DB::table('properties')->insert([
                'investment_id' => 4,
                'floor_id' => rand(18, 20),
                'name' => 'Mieszkanie '.$random_number,
                'name_list' => 'Mieszkanie',
                'number' => $random_number,
                'number_order' => $random_number,
                'rooms' => rand(1, 3),
                'area' => rand(10, 50),
                'type' => 0,
                'status' => rand(1, 4)
            ]);
        }
    }
}
