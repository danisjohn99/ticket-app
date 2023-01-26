<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role'=>'admin',
            'password' => bcrypt('admin@12345'),
        ]);

        User::create([
            'name' => 'Technician',
            'email' => 'technician@gmail.com',
            'role'=>'technician',
            'password' => bcrypt('technician@12345'),
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'role'=>'user',
            'password' => bcrypt('user@12345'),
        ]);

        User::create([
            'name' => 'Danis John',
            'email' => 'danisjohn99@gmail.com',
            'role'=>'user',
            'password' => bcrypt('user@12345'),
        ]);

    }
}