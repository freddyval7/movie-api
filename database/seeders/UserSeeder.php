<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin User',  'email' => 'admin@movie-api.test',  'role' => 'admin'],
            ['name' => 'Editor User', 'email' => 'editor@movie-api.test', 'role' => 'editor'],
            ['name' => 'Viewer User', 'email' => 'viewer@movie-api.test', 'role' => 'viewer'],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                $user,
                ['password' => bcrypt('password')]
            );
        }
    }
}
