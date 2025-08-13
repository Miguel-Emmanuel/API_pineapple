<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario de prueba - Evitar duplicados con firstOrCreate
        User::firstOrCreate(
            ['email' => 'tester@example.com'], // Buscar por email
            [
                'name' => 'Tester',
                'password' => Hash::make('password'),
            ]
        );

        // Productos de prueba - Solo crear si no existen
        if (Product::count() === 0) {
            Product::factory(10)->create();
        } else {
            $this->command->info('Productos ya existen, saltando creación...');
        }
    }
}
