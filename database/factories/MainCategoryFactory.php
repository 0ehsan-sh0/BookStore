<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MainCategory>
 */
class MainCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'url' => $this->faker->word(),
        ];
    }

    /**
     * Specify the exact data for the 'روانشناسی' category.
     */
    public function psychology(): MainCategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'روانشناسی',
                'url' => 'psychology',
            ];
        });
    }

    /**
     * Specify the exact data for the 'فلسفی' category.
     */
    public function philosophical(): MainCategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'فلسفی',
                'url' => 'philosophical',
            ];
        });
    }

    /**
     * Specify the exact data for the 'داستان کوتاه' category.
     */
    public function shortStory(): MainCategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'داستان کوتاه',
                'url' => 'short-story',
            ];
        });
    }

    /**
     * Specify the exact data for the 'سیاسی' category.
     */
    public function political(): MainCategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'سیاسی',
                'url' => 'political',
            ];
        });
    }

    /**
     * Specify the exact data for the 'تاریخی' category.
     */
    public function historical(): MainCategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'تاریخی',
                'url' => 'historical',
            ];
        });
    }
}
