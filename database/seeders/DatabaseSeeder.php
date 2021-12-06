<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Food;
use App\Models\Tag;
use App\Models\Ingredient;
use App\Models\Category;
use App\Models\TransFood;
use App\Models\TransCategory;
use App\Models\TransTag;
use App\Models\TransIngredient;

use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		$seedNumber = 100;
		
		Category::factory()->count(10)->create();
		Food::factory()->count($seedNumber)->create(); 
		Ingredient::factory()->count($seedNumber)->create();
		
		$faker = Faker::create();
		
		DB::table('languages')->insert(['name' => 'German', 'lang' => 'de']);
		DB::table('languages')->insert(['name' => 'French', 'lang' => 'fr']);
		
		
		for ($i = 1; $i <= $seedNumber; $i++) {
			Tag::factory()->create(['title' => 'Tag'.$i]);
		}
		
		for ($i = 1; $i <= $seedNumber; $i++) {
			DB::table('food_tag')->insert(['food_id' => $i, 'tag_id' => $faker->numberBetween($i, $seedNumber)]);
			DB::table('food_ingredient')->insert(['food_id' => $i, 'ingredient_id' => $faker->numberBetween($i, $seedNumber)]);
			
			TransFood::factory()->create(['food_id' => $i, 'language_id' => '1']);
			TransFood::factory()->create(['food_id' => $i, 'language_id' => '2']);
			
			if ($i <= 10) {
				TransCategory::factory()->create(['category_id' => $i, 'language_id' => '1']);
				TransCategory::factory()->create(['category_id' => $i, 'language_id' => '2']);
			}

			TransTag::factory()->create(['tag_id' => $i, 'language_id' => '1']);
			TransTag::factory()->create(['tag_id' => $i, 'language_id' => '2']);
			
			TransIngredient::factory()->create(['ingredient_id' => $i, 'language_id' => '1']);
			TransIngredient::factory()->create(['ingredient_id' => $i, 'language_id' => '2']);	
		}
	}
}
