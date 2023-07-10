<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            SettingsTableSeeder::class,
        ]);


        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'surname' => 'Admin',
            'username' => 'admin',
            'role_id' => User::ROLE_MODERATOR,
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        \App\Models\User::factory(20)->create();


//        \Illuminate\Support\Facades\Artisan::call('module:seed Page');
//        \Illuminate\Support\Facades\Artisan::call('module:seed Post');
//        \Illuminate\Support\Facades\Artisan::call('module:seed Service');
//        \Illuminate\Support\Facades\Artisan::call('module:seed Menu');

    }
}
