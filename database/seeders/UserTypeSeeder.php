<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserType::updateOrCreate(['name' => 'abc'], [
            'name' => 'abc',
            'description' => 'Type 1',
            'status' => 1,
        ]);

        UserType::updateOrCreate(['name' => 'xyz'], [
            'name' => 'xyz',
            'description' => 'Type 2',
            'status' => 1,
        ]);

    }
}
