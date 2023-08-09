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
                'keyword'        => 'Admin admin@admin.com 12345678922',
                'name'           => 'Admin',
                'surname'        => 'Admin',
                'phone'          => '12345678922',
                'role_id'        => User::ROLE_MODERATOR,
                'email'          => 'admin@admin.com',
                'password'       => Hash::make('password'), //bcrypt('password'),
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
