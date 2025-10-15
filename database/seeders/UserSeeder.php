<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            "name" => 'admin user',
            'email'=> 'admin@example.com',
            'password' => bcrypt('password'),
            'active' => true,
            'role' => 'admin'
        ]);

        DB::table('users')->insert([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            "name" => "Barista User",
            "email" => "barista@example.com",
            "password" => bcrypt("password"),
            "active" => true,
            "role" => "barista"
        ]);

        DB::table('users')->insert([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            "name" => "Barista user 2",
            "email" => "barista2@example.com",
            "password" => bcrypt("password"),
            "active" => false,
            "role" => "barista"
        ]);
    }
}
