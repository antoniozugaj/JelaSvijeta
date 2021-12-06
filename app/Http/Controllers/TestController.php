<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Tag;
use App\Models\Language;


class TestController extends Controller
{
	public function show(Request $request)
	{
		//Save data from request													
		$per_page = $request->input('per_page');
		$page = $request->input('page');
		$category = $request->input('category');
		$lang = $request->input('lang');
		$diff_time = $request->input('diff_time');
		$allTags[] = explode(',', str_replace(' ', '', $request->input('tags')));
        $with[] = explode(',', str_replace(' ', '', $request->input('with')));
		//Get full request url
		$self = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		//Get id of tags that are valid
        $validTags = $this->getValidTags($allTags);
		//Get valid "with" parameters
        $validWith = $this->getValidWith($with);
		
		//All dishes
        $query = Food::query();
        
		//Filter dishes by diff_time (simplified version as per task instructions)
        if (!isset($diff_time) && !is_numeric($diff_time)) { 
            $query->where('status', 'created');
        } elseif ((int)$diff_time < 0) {
				$query->where('status', 'created');	
		}

		//Filter dishes by category
        if (is_numeric($category)) { 
            $query->where('category_id', $category);
        }
		if ($category == "NULL") {
			$query->where('category_id', null);
		}

		//Filter dishes by tags
        if ($validTags) {

            $query->join('food_tag', 'food.id', '=', 'food_tag.food_id')
				->join('tags', 'tags.id', '=', 'food_tag.tag_id')
				->whereIn('tags.id', $validTags);
        }

		//Filter dishes by language
        if ($lang != 'eng') {

            $query->join('trans_foods', 'food.id', '=', 'trans_foods.food_id')
                ->join('languages', 'languages.id', '=', 'trans_foods.language_id')
                ->where('lang', $lang)
                ->select('food.id', 'trans_foods.title', 'trans_foods.description', 'food.status');
        } else {
			$query->select('food.id', 'food.title', 'food.description', 'food.status');
		}

        $query = $query->distinct()->get();

		$categoryArray;
		$tagArray;
		$ingredientArray;
		$dataArray=array();

		//Add "with" data to dishes
		foreach($query as $dish) {

			if (in_array('category', $validWith)) {
				//Get dish categoriey
				$categoryArray = Category::join('food', 'food.category_id', '=', 'categories.id')
					->where('food.id', $dish->id);
				
				//Select language for category
				if ($lang != 'eng') {
					$categoryArray->join('trans_categories', 'trans_categories.category_id', '=', 'caegories.id')
						->join('language', 'language.id', '=', 'trans_categories.language_id')
						->where('lang', $lang)
						->select('categories.id', 'trans_categories.title', 'categories.slug');
				} else {
					$categoryArray->select('categories.id', 'categories.title', 'categories.slug');
				}
				$categoryArray=$categoryArray->get(); 

				//Dish + category
				$json =response()->json([
					'id' => $dish->id,
					'title' => $dish->title,
					'description' => $dish->description,
					'status' => $dish->status,
					'category' => $categoryArray
				]);
			}
			
			if (in_array('tag', $validWith)) {
				//Get dish tags 
				$tagArray = Food::join('food_tag', 'food.id', '=', 'food_tag.food_id')
					->join('tags', 'tags.id', '=', 'food_tag.tag_id')
					->where('food.id', $dish->id);
					
				//Select language for tags
				if ($lang != 'eng') {
					$tagArray->join('trans_tag', 'trans_tag.tag_id', '=', 'tags.id')
						->join('language', 'language.id', '=', 'trans_tags.language_id')
						->where('lang', $lang)
						->select('tags.id', 'trans_tags.title', 'tags.slug');
				} else {
					$tagArray->select('tags.id', 'tags.title', 'tags.slug');
				}
				$tagArray = $tagArray->get();

				//Dish + tags
				$json = response()->json([
					'id' => $dish->id,
					'title' => $dish->title,
					'description' => $dish->description,
					'status' => $dish->status,
					'tags' => $tagArray
				]);
			}
	
			if (in_array('ingredient', $validWith)) {
				//Get dish ingredients
				$ingredientArray = Food::join('food_ingredient', 'food.id', '=', 'food_ingredient.food_id')
					->join('ingredients', 'ingredients.id', '=', 'food_ingredient.ingredient_id')
					->where('food.id', $dish->id);
					
				//Select ingredients language
				if ($lang != 'eng') {
					$ingredientArray->join('trans_ingredients', 'trans_ingredients.ingredient_id', '=', 'ingredients.id')
						->join('language', 'language.id', '=', 'trans_ingredients.language_id')
						->where('lang', $lang)
						->select('ingredients.id', 'trans_ingredients.title', 'ingredients.slug');
				} else {
					$ingredientArray->select('ingredients.id', 'ingredients.title', 'ingredients.slug');
				}
				$ingredientArray = $ingredientArray->get();

				//Dish + ingredients
				$json =response()->json([
					'id' => $dish->id,
					'title' => $dish->title,
					'description' => $dish->description,
					'status' => $dish->status,
					'ingredients' => $ingredientArray
				]);
			}

			if (in_array('category', $validWith) && in_array('ingredient', $validWith)) {
				//Dish + category + ingredient
				$json = response()->json([
					'id' => $dish->id,
					'title' => $dish->title,
					'description' => $dish->description,
					'status' => $dish->status,
					'category' => $categoryArray,
					'ingredients' => $ingredientArray
				]);
			}

			if (in_array('category', $validWith) && in_array('tag', $validWith)) {
				//Dish + category + tags
				$json = response()->json([
					'id' => $dish->id,
					'title' => $dish->title,
					'description' => $dish->description,
					'status' => $dish->status,
					'category' => $categoryArray,
					'tags' => $tagArray
				]);
			}

			if (in_array('tag', $validWith) && in_array('ingredient', $validWith)) {
				//Dish + tags + ingredients
				$json = response()->json([
					'id'=> $dish->id,
					'title' => $dish->title,
					'description' => $dish->description,
					'status' => $dish->status,
					'tags' => $tagArray,
					'ingredients' => $ingredientArray
				]);
			}

			if (in_array('category', $validWith) && in_array('ingredient', $validWith) && in_array('tag', $validWith)) {
				//Dish + category + tags + ingredients 
				$json = response()->json([
					'id' => $dish->id,
					'title' => $dish->title,
					'description' => $dish->description,
					'status' => $dish->status,
					'category' => $categoryArray,
					'tags' => $tagArray,
					'ingredients' => $ingredientArray
				]);
			}

			if (!in_array('category', $validWith) && !in_array('ingredient', $validWith) && !in_array('tag', $validWith)) {
				//Only dish
				$json = response()->json([
					'id' => $dish->id,
					'title' => $dish->title,
					'description' => $dish->description,
					'status' => $dish->status,
				]);
			}
			array_push($dataArray, $json);
		}

		//Data that will fill json response 
		$data=array();

		//Check if per_page is set and if not, set to 15
		if (!isset($per_page) or !is_numeric($per_page) or (int) $per_page < 1) {
			$per_page = 15;
		}		
		
		//Calculate total pages
		$totalPages = ceil((float) count($dataArray) / $per_page);
		
		//Check if page is set and if not, set to first or to the last page
		if (!isset($page) or !is_numeric($page) or (int) $page < 1) {
			$page = 1;
		}	
		if ((int) $page > $totalPages) {
			$page = $totalPages;
		}

		//Select dishes for the corresponding page
		for ($i =( $page - 1) * $per_page; $i < count($dataArray) && $i < $page * $per_page; $i++) {				

			array_push($data, $dataArray[$i]->original);
		}

		//Correct url with page
		if (substr($self, -1) == "=") {
			$self = $self."1";
		} 

		//Link for previous 
		if ($page > 1) {
			$prev = substr_replace($self,(int) $page - 1, -1);
		} else {
			$prev = 0;
		}
		
		//Link for next 
		if ($page < $totalPages) {
			$next = substr_replace($self,(int) $page + 1, -1);
		} else {
			$next = 0;
		}
		
		//Fill and return Json full response
		return response()->json(['meta'=>[
				'CurrentPage' => (int) $page,
				'totalItems' => count($dataArray),
				'itmesPerPage' => (int) $per_page,
				'totalPages' => $totalPages],
			'data' => $data,
			'links' => [
				'prev' => $prev,
				'next' => $next,
				'self' => $self]
		]);
	}

    public function getValidTags($allTags)
	{
		$tags = $allTags[0];
		$validTags = array();
																	
		for ($i=0; $i < count($tags); $i++) {
			$element = Tag::where('title', [ucfirst($tags[$i])])->select('id')->get();
			if ($element != '[]') {
				$num = (int) (filter_var($element, FILTER_SANITIZE_NUMBER_INT));
				array_push($validTags, $num);
			}
		}
		return $validTags;
	}

    public function getValidWith($allWith)
	{
		$with = $allWith[0];
		$validWith = array();
		
																	
		for ($i=0; $i < count($with); $i++) {
		    if (str_contains(strtolower($with[$i]), 'categor')) {	
				array_push($validWith, 'category');
			}
            if (str_contains(strtolower($with[$i]), 'tag')) {
				array_push($validWith, 'tag');
			}
            if (str_contains(strtolower($with[$i]), 'ingredient')) {
				array_push($validWith, 'ingredient');
			}
		}
		return $validWith;
	}

	public function form()
	{
		$data_category = Category::all();
		$data_language = Language::all();
		
		return view('index', ['category' => $data_category, 'language' => $data_language]);
	}

}