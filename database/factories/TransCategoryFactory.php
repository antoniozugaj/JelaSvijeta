<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TransCategory;

class TransCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
	
	protected $model = TransCategory::class;
	 
    public function definition()
    {
		$faker = \Faker\Factory::create();
		$faker->addProvider(new \FakerRestaurant\Provider\de_DE\Restaurant($faker));
		
        return [
            'title'=>$faker->randomElement(['le category 2', 'le category 1', 'le category 3', 'le category 4', 'le category 5']),
        ];
    }
}
