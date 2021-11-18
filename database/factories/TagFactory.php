<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tag;

class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Tag::class;
	
	public function definition()
    {
        return [
            //'title' => $this->faker->randomElement(['Tag1', 'Tag2', 'Tag3', 'Tag4', 'Tag5', 'Tag10', 'Tag6', 'Tag7', 'Tag8', 'Tag9']),
			'slug' => $this->faker->bothify('?????')
        ];
    }
}
