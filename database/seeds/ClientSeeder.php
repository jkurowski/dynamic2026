<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 97; $i++) {
            DB::table('clients')->insert([
                'name' => $faker->name(),
                'mail' => $faker->safeEmail,
                'ip' => $faker->ipv4,
                'host' => $faker->domainName,
                'browser' => $faker->userAgent,
                'timestamp' => $faker->unixTime,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
