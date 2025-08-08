<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario de prueba
        User::create([
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'password' => Hash::make('password')
        ]);

        // Productos de prueba
        Product::factory(10)->create();
    }
}
