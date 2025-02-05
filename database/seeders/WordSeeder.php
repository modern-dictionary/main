<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Word;
use App\Models\User;

class WordSeeder extends Seeder
{
    public function run(): void
    {
        // Get all user IDs
        $userIds = User::pluck('id')->toArray();

        if (empty($userIds)) {
            throw new \Exception('No users found. Please run UserSeeder first.');
        }

        // Create initial Persian words
        $persianWords = [
            [
                'word' => 'سلام',
                'meaning' => 'Hello',
                'pronunciation' => 'Salaam',
                'description' => 'A common greeting in Persian',
            ],
            [
                'word' => 'خداحافظ',
                'meaning' => 'Goodbye',
                'pronunciation' => 'Khodahafez',
                'description' => 'A farewell greeting in Persian',
            ],
            // Add more Persian words here
        ];

        $daysAgo = now()->subDays(30);

        // Add Persian words with varied dates
        foreach ($persianWords as $wordData) {
            Word::create(array_merge($wordData, [
                'user_id' => fake()->randomElement($userIds),
                'created_at' => $daysAgo->copy()->addDays(fake()->numberBetween(0, 5)),
            ]));
        }

        // Create 10-20 random words per day for the last 30 days
        for ($day = 0; $day < 30; $day++) {
            $date = $daysAgo->copy()->addDays($day);
            $wordsToCreate = fake()->numberBetween(10, 20);

            for ($i = 0; $i < $wordsToCreate; $i++) {
                Word::create([
                    'word' => fake()->word(),
                    'meaning' => fake()->words(3, true),
                    'pronunciation' => fake()->word(),
                    'description' => fake()->sentence(),
                    'user_id' => fake()->randomElement($userIds),
                    'created_at' => $date->copy()->addHours(fake()->numberBetween(0, 23))->addMinutes(fake()->numberBetween(0, 59)),
                ]);
            }
        }
    }
}
