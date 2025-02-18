<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo "::::::::: START SEED :::::::::\n";
        $users = User::factory(10)->create();

        foreach ($users as $user) {
            echo "... seeding ...\n";
            Task::factory()
                ->count(4)
                ->for($user)
                ->create();
        }

        User::factory()->create([
            'name' => 'Denis',
            'email' => 'denis@denis.com',
            'email_verified_at' => now(),
            'password' => Hash::make('denis123'),
            'remember_token' => Str::random(10),
        ]);

        echo "::::::::: END SEED :::::::::";
    }
}
