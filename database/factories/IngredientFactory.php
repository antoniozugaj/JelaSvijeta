<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ingredient;

class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Ingredient::class;
	
	public function definition()
    {
		$this->faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($this->faker));
		
        return [
            'title' => $this->faker->meatName(),
			'slug' => $this->faker->bothify('?????')
        ];
    }
}
