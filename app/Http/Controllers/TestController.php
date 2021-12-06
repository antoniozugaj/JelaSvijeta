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
	/*
	Task done with Eloquent relationships
	*/

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
        

		//Filter dishes by tags
        if ($validTags) {

            $query->whereHas('tag', function ($var) use ($validTags) {
				$var->whereIn('id', $validTags);
			});
        }

		//Add "with" data to dishes
		if (in_array('category', $validWith)) {
			//Dish + category
			if ($lang != 'eng') {
				$query->with('category.transCategory');
			} else {
				$query->with('category');
			}
		}
		
		if (in_array('tag', $validWith)) {
			//Dish + tag 
			if ($lang != 'eng') {
				$query->with('tag.transTag');
			} else {
				$query->with('tag');
			}
		}

		if (in_array('ingredient', $validWith)) {
			//Dish + ingredient
			if ($lang != 'eng') {
				$query->with('ingredient.transIngredient');
			} else {
				$query->with('ingredient');
			}
		}

		if (in_array('category', $validWith) && in_array('ingredient', $validWith)) {
			//Dish + category + ingredient
			if ($lang != 'eng') {
				$query->with('category.transCategory','ingredient.transIngredient');
			} else {
				$query->with('category','ingredient');
			}
		}

		if (in_array('category', $validWith) && in_array('tag', $validWith)) {
			//Dish + category + tags
			if ($lang != 'eng') {
				$query->with('category.transCategory','tag.transTag');
			} else {
				$query->with('category','tag');
			}
		}

		if (in_array('tag', $validWith) && in_array('ingredient', $validWith)) {
			//Dish + tags + ingredients
			if ($lang != 'eng') {
				$query->with('tag.transTag','ingredient.transIngredient');
			} else {
				$query->with('tag','ingredient');
			}
		}

		if (in_array('category', $validWith) && in_array('ingredient', $validWith) && in_array('tag', $validWith)) {
			//Dish + category + tags + ingredients 
			if ($lang != 'eng') {
				$query->with('category.transCategory','tag.transTag','ingredient.transIngredient');
			} else {
				$query->with('category','ingredient','tag');
			}
		}

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


		//Filter dishes by language
		if ($lang != 'eng') {
            $query->with('transFood');
        } else {
			$query->select('id', 'title', 'description', 'status','category_id');
		}

		//Fetch all needed data
		$query = $query->distinct()->get();

		//Data that will fill json response 
		$data=array();

		//Check if per_page is set and if not, set to 15
		if (!isset($per_page) or !is_numeric($per_page) or (int) $per_page < 1) {
			$per_page = 15;
		}		
		
		//Calculate total pages
		$totalPages = ceil((float) count($query) / $per_page);
		
		//Check if page is set and if not, set to first or to the last page
		if (!isset($page) or !is_numeric($page) or (int) $page < 1) {
			$page = 1;
		}	
		if ((int) $page > $totalPages) {
			$page = $totalPages;
		}

		if(count($query)){
			//Select dishes for the corresponding page
			for ($i =( $page - 1) * $per_page; $i < count($query) && $i < $page * $per_page; $i++) {				
				array_push($data, $query[$i]);
			}
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
				'totalItems' => count($query),
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