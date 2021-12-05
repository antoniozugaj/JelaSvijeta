<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Food;
use App\Models\Tag;
use App\Models\Ingredient;
use App\Models\Category;
use App\Models\Trans_food;
use App\Models\Trans_category;
use App\Models\Trans_tag;
use App\Models\Trans_ingredient;

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
			
			Trans_food::factory()->create(['food_id' => $i, 'language_id' => '1']);
			Trans_food::factory()->create(['food_id' => $i, 'language_id' => '2']);
			
			if ($i <= 10) {
				Trans_category::factory()->create(['category_id' => $i, 'language_id' => '1']);
				Trans_category::factory()->create(['category_id' => $i, 'language_id' => '2']);
			}

			Trans_tag::factory()->create(['tags_id' => $i, 'language_id' => '1']);
			Trans_tag::factory()->create(['tags_id' => $i, 'language_id' => '2']);
			
			Trans_ingredient::factory()->create(['ingredient_id' => $i, 'language_id' => '1']);
			Trans_ingredient::factory()->create(['ingredient_id' => $i, 'language_id' => '2']);	
		}
	}
}
