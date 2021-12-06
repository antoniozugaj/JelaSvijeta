<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TransFood;

class TransFoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
	
	protected $model = TransFood::class;		
	
    public function definition()
    {
		
		
		$faker = \Faker\Factory::create();
		$faker->addProvider(new \FakerRestaurant\Provider\de_DE\Restaurant($faker));
		
        return [
			'title' => $faker->foodName(),
			'description' => $faker->text(),
        ];
		
    }
}
