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
            "id" => '1',
            "name" => "Kopi Susu",
            "price" => rand(10000, 50000),
            "active" => true
        ]);

         DB::table("products")->insert([
            "id" => '2',
            "name" => "Kopi Gula Aren",
            "price" => rand(10000, 50000),
            "active" => true
        ]);

        DB::table("products")->insert([
            "id" => '3',
            "name" => "Creamy Latte",
            "price" => rand(10000, 50000),
            "active" => true
        ]);

        DB::table("products")->insert([
            "id" => '4',
            "name" => "Charcoal",
            "price" => rand(10000, 50000),
            "active" => true
        ]);

        DB::table("products")->insert([
            "id" => '5',
            "name" => "Matcha",
            "price" => rand(10000, 50000),
            "active" => true
        ]);
    }
}
