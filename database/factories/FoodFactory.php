<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Food;


class FoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
	 
	protected $model = Food::class;
	
	 
    public function definition()
    {
		$seed_num = 10;   //treba nekak dobit seed_num iz DatabaseSeedera
		
		$this->faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($this->faker));
		
        return [
            'title' => $this->faker->unique()->foodName(),
			'category_id' => $this->faker->optional($weight = 0.8)->numberBetween(1,$seed_num),
			'status' => $this->faker->randomElement(['created', 'deleted']),
			'description' => $this->faker->text()
			
        ];
    }
}
