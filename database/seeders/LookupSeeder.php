<?php

namespace Database\Seeders;

use App\Models\Eula;
use App\Models\Infographic;
use App\Models\Lookup;
use App\Models\UserType;
use Illuminate\Database\Seeder;

class LookupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lookup::updateOrCreate(['id' => 1, 'name' => 'Normal user', 'group_code' => 1], [
            'id' => 1,
            'name' => 'Normal user',
            'description' => 'Type 1',
            'group_code' => 1,
            'status' => 1
        ]);

        Lookup::updateOrCreate(['id' => 2, 'name' => 'Institution user', 'group_code' => 1], [
            'id' => 2,
            'name' => 'Institution user',
            'description' => 'Type 1',
            'group_code' => 1,
            'status' => 1
        ]);

        Lookup::updateOrCreate(['id' => 51, 'name' => 'Customer', 'group_code' => 2], [
            'id' => 51,
            'name' => 'Customer',
            'group_code' => 2,
            'description' => 'Contact us reason for investment',
            'status' => 1
        ]);


        Lookup::updateOrCreate(['id' => 52, 'name' => 'Press/Media', 'group_code' => 2], [
            'id' => 52,
            'name' => 'Press/Media',
            'group_code' => 2,
            'description' => 'Contact us reason for investment',
            'status' => 1
        ]);

        Lookup::updateOrCreate(['id' => 53, 'name' => 'Investor relations', 'group_code' => 2], [
            'id' => 53,
            'name' => 'Investor relations',
            'group_code' => 2,
            'description' => 'Contact us reason for investment',
            'status' => 1
        ]);

        Lookup::updateOrCreate(['id' => 54, 'name' => 'Charity', 'group_code' => 2], [
            'id' => 54,
            'name' => 'Charity',
            'group_code' => 2,
            'description' => 'Contact us reason for investment',
            'status' => 1
        ]);

        Lookup::updateOrCreate(['id' => 55, 'name' => 'Pledge', 'group_code' => 2], [
            'id' => 55,
            'name' => 'Pledge',
            'group_code' => 2,
            'description' => 'Contact us reason for investment',
            'status' => 1
        ]);


        Lookup::updateOrCreate(['id' => 56, 'name' => 'Private equity', 'group_code' => 2], [
            'id' => 56,
            'name' => 'Private equity',
            'group_code' => 2,
            'description' => 'Contact us reason for investment',
            'status' => 1
        ]);

        Lookup::updateOrCreate(['id' => 57, 'name' => 'SPAC', 'group_code' => 2], [
            'id' => 57,
            'name' => 'SPAC',
            'group_code' => 2,
            'description' => 'Contact us reason for investment',
            'status' => 1
        ]);

        Lookup::updateOrCreate(['id' => 58, 'name' => 'SPV', 'group_code' => 2], [
            'id' => 58,
            'name' => 'SPV',
            'group_code' => 2,
            'description' => 'Contact us reason for investment',
            'status' => 1
        ]);

        Lookup::updateOrCreate(['id' => 59, 'name' => 'DAOs', 'group_code' => 2], [
            'id' => 59,
            'name' => 'DAOs',
            'group_code' => 2,
            'description' => 'Contact us reason for investment',
            'status' => 1
        ]);

        Lookup::updateOrCreate(['id' => 60, 'name' => 'Carbon Credits', 'group_code' => 2], [
            'id' => 60,
            'name' => 'Carbon Credits',
            'group_code' => 2,
            'description' => 'Contact us reason for investment',
            'status' => 1
        ]);

        Lookup::updateOrCreate(['id' => 61, 'name' => 'Blockchain', 'group_code' => 2], [
            'id' => 61,
            'name' => 'Blockchain',
            'group_code' => 2,
            'description' => 'Contact us reason for investment',
            'status' => 1
        ]);

        Lookup::updateOrCreate(['id' => 62, 'name' => 'ICO', 'group_code' => 2], [
            'id' => 62,
            'name' => 'Blockchain',
            'group_code' => 2,
            'description' => 'Contact us reason for investment',
            'status' => 1
        ]);

        Lookup::updateOrCreate(['id' => 63, 'name' => 'ICO', 'group_code' => 2], [
            'id' => 63,
            'name' => 'Governance',
            'group_code' => 2,
            'description' => 'Contact us reason for investment',
            'status' => 1
        ]);

        Eula::updateOrCreate(['name' => 'Eula'],[
            'name' => 'Eula',
            'file_url' => 'eula\dummy.pdf',
            'description' => 'End user license agreement',
            'status' => 1
        ]);

        Infographic::updateOrCreate(['name' => 'Infographic'],[
            'name' => 'Infographic',
            'file_url' => 'infographic\infographic.png',
            'description' => 'Infographic',
            'status' => 1
        ]);


    }
}
