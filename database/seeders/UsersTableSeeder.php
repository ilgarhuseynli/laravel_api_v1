<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'role_id'        => User::ROLE_MODERATOR,
                'email'          => 'admin@admin.com',
                'password'       => Hash::make('password'), //bcrypt('password'),
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
