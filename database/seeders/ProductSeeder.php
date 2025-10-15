<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("products")->insert([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            "name" => "Kopi Susu",
            "price" => rand(10000, 50000),
            "active" => true
        ]);

         DB::table("products")->insert([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            "name" => "Kopi Gula Aren",
            "price" => rand(10000, 50000),
            "active" => true
        ]);

        DB::table("products")->insert([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            "name" => "Creamy Latte",
            "price" => rand(10000, 50000),
            "active" => true
        ]);

        DB::table("products")->insert([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            "name" => "Charcoal",
            "price" => rand(10000, 50000),
            "active" => true
        ]);

        DB::table("products")->insert([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            "name" => "Matcha",
            "price" => rand(10000, 50000),
            "active" => true
        ]);
    }
}
