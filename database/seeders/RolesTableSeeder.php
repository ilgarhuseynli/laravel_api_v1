<?php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'    => 1,
                'title' => 'Moderator',
            ],
            [
                'id'    => 2,
                'title' => 'Employee',
            ],
            [
                'id'    => 3,
                'title' => 'User',
            ],
        ];

        Role::insert($roles);
    }
}
