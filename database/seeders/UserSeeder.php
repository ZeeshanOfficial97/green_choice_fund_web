<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userCreated = User::updateOrCreate(['email' => 'admin@gmail.com'], [
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => \Carbon\Carbon::now(),
            'password' => bcrypt('12345678'),
            'country_code' => '+92',
            'contact_no' => '3086929835',
            'profile_photo_path' => '',
            'privacy_policy_version' => '1.0',
            'is_notification_enabled' => false,
            'status' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ])->assignRole('super_admin');

        $super_admin = Role::findByName('super_admin', 'api');
        if ($super_admin) {
            $userCreated->assignRole($super_admin);
        }
    }
}
