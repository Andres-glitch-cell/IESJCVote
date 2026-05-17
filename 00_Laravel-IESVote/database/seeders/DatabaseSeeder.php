<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            // [IMPORTANT] Se cambiaron los campos de name, email, password a name, dni, is_admin
            'name' => 'Test User',
            'dni' => '12345678',
            'is_admin' => true,
        ]);

        User::factory()->count(10)->create();
    }
}
