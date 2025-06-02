<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Supermarket;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supermarketExternalIds = [
            1977593425,
            1977593425,
            6929354836,
            6941632282,
            7173963008,
        ];

        foreach ($supermarketExternalIds as $externalId) {
            $supermarket = Supermarket::where('external_id', $externalId)->first();

            Review::create([
                'user_id' => fake()->numberBetween(1, 2),
                'supermarket_id' => $supermarket->id,
                'content' => fake()->sentence(),
            ]);
        }
    }
}
