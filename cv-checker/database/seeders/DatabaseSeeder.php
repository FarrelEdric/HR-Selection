<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(JobSeeder::class);

        User::updateOrCreate(
            ['email' => 'admin@hrhub.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator Gmail',
                'password' => bcrypt('admin'),
            ]
        );
    }
}
