<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@kamakhya.com'], [
            'name' => 'Kamakhya Administrator',
            'password' => 'password@123',
            'is_admin' => true,
        ]);
    }
}
