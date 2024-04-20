<?php

namespace Database\Factories;

use App\Enums\QuestionType;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(QuestionType::cases());

        return [
            'category_id' => $this->faker->randomElement(Category::all()->pluck('id')->toArray()),
            'question' => $this->faker->sentence(),
            'type' => $type,
        ];
    }
}
