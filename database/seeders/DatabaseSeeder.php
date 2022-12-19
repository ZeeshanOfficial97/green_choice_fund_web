<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PushNotificationPlateformSeeder::class);
        $this->call(InfoUrlSeeder::class);
        $this->call(LookupSeeder::class);

        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);

    }
}
