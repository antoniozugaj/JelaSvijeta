<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TransIngredient;

class TransIngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
		
		$faker = \Faker\Factory::create();
		$faker->addProvider(new \FakerRestaurant\Provider\de_DE\Restaurant($faker));
		
        return [
            'title' => $faker->dairyName()
        ];
    }
}
