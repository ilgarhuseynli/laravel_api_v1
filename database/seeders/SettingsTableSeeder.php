<?php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {

        $settings = [
            [
                'key'   => 'title',
                'value' => 'Water service'
            ],
            [
                'key'   => 'description',
                'value' => 'Water service desc'
            ],
            [
                'key'   => 'email',
                'value' => 'info@ultrafixappliance.com'
            ],
            [
                'key'   => 'address',
                'value' => 'Nərimanov rayonu Albert Aqarunov küçəsi 14a-22b'
            ],

            [
                'key'   => 'phone',
                'value' => '(+994 55) 555-55-55'
            ],

            [
                'key'   => 'opening_hours',
                'value' => '09:00 - 18:00'
            ],
            [
                'key'   => 'social_fb',
                'value' => 'facebook.com'
            ],
            [
                'key'   => 'social_twitter',
                'value' => 'twitter.com'
            ],
            [
                'key'   => 'social_instagram',
                'value' => 'instagram.com'
            ],
            [
                'key'   => 'social_linkedin',
                'value' => 'linkedin.com'
            ],
            [
                'key'   => 'social_youtube',
                'value' => 'youtube.com'
            ],
            [
                'key'   => 'social_wp',
                'value' => 'wp'
            ],
            [
                'key'   => 'social_telegram',
                'value' => 'telegram'
            ],
        ];

        foreach($settings as $setting)
        {
            Setting::create($setting);
        }
    }
}
