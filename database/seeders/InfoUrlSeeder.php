<?php

namespace Database\Seeders;

use App\Models\InfoUrl;
use Illuminate\Database\Seeder;

class InfoUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InfoUrl::updateOrCreate(['url_key' => 'privacy_policy'], [
            'url_key' => 'privacy_policy',
            'url_version' => '1.0',
            'url_web' => 'https://greenchoicefund.com/privacy-policy/',
            'updated_date' => \Carbon\Carbon::now(),
        ]);

        InfoUrl::updateOrCreate(['url_key' => 'terms_and_condition'], [
            'url_key' => 'terms_and_condition',
            'url_version' => '1.0',
            'url_web' => 'https://greenchoicefund.com/privacy-policy/',
            'updated_date' => \Carbon\Carbon::now(),
        ]);

        InfoUrl::updateOrCreate(['url_key' => 'faq'], [
            'url_key' => 'faq',
            'url_version' => '1.0',
            'url_web' => 'https://greenchoicefund.com/',
            // 'url_web_url' => 'https://admin.greenchoicefund.com/apps/faqs/list/view'
            'updated_date' => \Carbon\Carbon::now(),
        ]);

        InfoUrl::updateOrCreate(['url_key' => 'about_us'], [
            'url_key' => 'about_us',
            'url_version' => '1.0',
            'url_web' => 'https://greenchoicefund.com/',
            'updated_date' => \Carbon\Carbon::now(),
        ]);

    }
}
