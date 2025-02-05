<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user only if it doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'created_at' => now()->subDays(30),
            ]
        );

        // Create users with different dates over the last 30 days
        $daysAgo = now()->subDays(30);
        $usedEmails = ['admin@example.com']; // Track used emails

        // Create 5-10 users per day for the last 30 days
        for ($day = 0; $day < 30; $day++) {
            $date = $daysAgo->copy()->addDays($day);
            $usersToCreate = fake()->numberBetween(5, 10);

            for ($i = 0; $i < $usersToCreate; $i++) {
                // Generate a unique email
                do {
                    $email = fake()->unique()->safeEmail();
                } while (in_array($email, $usedEmails));

                $usedEmails[] = $email;

                User::create([
                    'name' => fake()->name(),
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'created_at' => $date->copy()->addHours(fake()->numberBetween(0, 23))->addMinutes(fake()->numberBetween(0, 59)),
                    'updated_at' => $date->copy()->addHours(fake()->numberBetween(0, 23))->addMinutes(fake()->numberBetween(0, 59)),
                ]);
            }
        }
    }
}
