<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    /**
     * Specify the exact data for the 'خودشناسی' category.
     */
    public function selfKnowledge(): CategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'خودشناسی',
                'url' => 'self-knowledge',
                'main_category_id' => 1,
            ];
        });
    }

    /**
     * Specify the exact data for the 'ادبیات آمریکا' category.
     */
    public function americanLiterature(): CategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'ادبیات آمریکا',
                'url' => 'american-literature',
                'main_category_id' => 2,
            ];
        });
    }

    /**
     * Specify the exact data for the 'فانتزی' category.
     */
    public function fantasy(): CategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'فانتزی',
                'url' => 'fantasy',
                'main_category_id' => 3,
            ];
        });
    }

    /**
     * Specify the exact data for the 'سیاست ایران' category.
     */
    public function iranPolitical(): CategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'سیاست ایران',
                'url' => 'iran-political',
                'main_category_id' => 4,
            ];
        });
    }

    /**
     * Specify the exact data for the 'اشکانیان' category.
     */
    public function parthians(): CategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'اشکانیان',
                'url' => 'Parthians',
                'main_category_id' => 5,
            ];
        });
    }

    /**
     * Specify the exact data for the 'روانکاوی' category.
     */
    public function psychoanalysis(): CategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'روانکاوی',
                'url' => 'psychoanalysis',
                'main_category_id' => 1,
            ];
        });
    }

    /**
     * Specify the exact data for the 'ادبیات فرانسه' category.
     */
    public function frenchLiterature(): CategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'ادبیات فرانسه',
                'url' => 'french-literature',
                'main_category_id' => 2,
            ];
        });
    }

    /**
     * Specify the exact data for the 'ماجراجویی' category.
     */
    public function adventure(): CategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'ماجراجویی',
                'url' => 'adventure',
                'main_category_id' => 3,
            ];
        });
    }

    /**
     * Specify the exact data for the 'سیاست روسیه' category.
     */
    public function russianPolitical(): CategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'سیاست روسیه',
                'url' => 'russian-political',
                'main_category_id' => 4,
            ];
        });
    }

    /**
     * Specify the exactdata for the 'تاریخ یونان' category.
     */
    public function historyOfGreece(): CategoryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'تاریخ یونان',
                'url' => 'history-of-greece',
                'main_category_id' => 5,
            ];
        });
    }
}
