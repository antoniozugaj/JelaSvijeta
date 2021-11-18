<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Trans_tag;

class Trans_tagFactory extends Factory
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
            'title' => $faker->randomElement(['das Tag1', 'das Tag2', 'das Tag3', 'das Tag4', 'das Tag5', 'das Tag10', 'das Tag6', 'das Tag7', 'das Tag8', 'das Tag9'])
        ];
    }
}
