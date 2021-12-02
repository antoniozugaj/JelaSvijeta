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
	function Form(){
		
		$data_category = Category::all();
		$data_language=Language::all();
		
		return view('index', [ 'category' => $data_category, 'language'=>$data_language]);
	}
	
	function Show(Request $request){
		
		
																	//REQUEST
		$per_page= $request->input('per_page');
		
		$page = $request->input('page');
		
		$category = $request->input('category');
		
		$lang = $request->input('lang');
		
		$diff_time = $request->input('diff_time');
		
		$allTags[] = explode(',',str_replace(' ', '', $request->input('tags')));

        $with[] = explode(',',str_replace(' ', '', $request->input('with')));

        $validTags= $this->GetValidTags($allTags);

        $validWith= $this->GetValidWith($with);
		


		///////////////////////////////////////////////////////////////////

		if(isset($per_page))									//ispravnost per_page
		
			if( !is_numeric($per_page))
				
				return view('error', [ 'error' => 'Dishes per page atributes must be a number']);
		
		if(isset($page))										//ispravnost page
		
			if( !is_numeric($page))
				
				return view('error', [ 'error' => 'Page atributes must be a number']);
        /////////////////////////////////////////////////////////////////////////


        $query = Food::query();
        
        if(!isset($diff_time)){ /////////////////////

            $query->where('status','created');
        }
        if(is_numeric($category)){ ////////////////////ispravi

            $query->where('category_id',$category);
        }
        if($validTags){

            $query->join('food_tag','food.id','=','food_tag.food_id')
				->join('tags','tags.id','=','food_tag.tag_id')
				->whereIn('tags.id',$validTags);

        }
        if($lang != 'eng'){

            $query->join('trans_foods','food.id','=','trans_foods.food_id')
                ->join('languages','languages.id','=','trans_foods.language_id')
                ->where('lang',$lang)
                ->select('food.id','trans_foods.title','trans_foods.description','food.status');
        }

        
        $query=$query->get();


		$foodId = $this->GetFoodId($query);

		$categoryArray;
		$tagArray;
		$ingredientArray;

		if(in_array('category',$validWith)){

            $categoryArray= Category::join('food','food.category_id','=','categories.id')
				->whereIn('food.id',$foodId);
				
			
			if($lang != 'eng'){
					$categoryArray->join('trans_categories','trans_categories.category_id','=','caegories.id')
						->join('language','language.id','=','trans_categories.language_id')
						->where('lang',$lang)
						->select('categories.id','trans_categories.title','categories.slug');
			}else{
				$categoryArray->select('categories.id','categories.title','categories.slug');
			}

			$categoryArray=$categoryArray->get();
		}
		
        if(in_array('tag',$validWith)){

            $tagArray= Food::join('food_tag','food.id','=','food_tag.food_id')
				->join('tags','tags.id','=','food_tag.tag_id')
				->whereIn('food.id',$foodId);
				

			if($lang != 'eng'){
				$tagArray->join('trans_tag','trans_tag.tag_id','=','tags.id')
					->join('language','language.id','=','trans_tags.language_id')
					->where('lang',$lang)
					->select('tags.id','trans_tags.title','tags.slug');

			}else{
				$tagArray->select('tags.id','tags.title','tags.slug');
			}
			$tagArray=$tagArray->get();
        }

        if(in_array('ingredient',$validWith)){

            $ingredientArray= Food::join('food_ingredient','food.id','=','food_ingredient.food_id')
				->join('ingredients','ingredients.id','=','food_ingredient.ingredient_id')
				->whereIn('food.id',$foodId);
				

			if($lang != 'eng'){
				$ingredientArray->join('trans_ingredients','trans_ingredients.ingredient_id','=','ingredients.id')
					->join('language','language.id','=','trans_ingredients.language_id')
					->where('lang',$lang)
					->select('ingredients.id','trans_ingredients.title','ingredients.slug');
			}else{
				$ingredientArray->select('ingredients.id','ingredients.title','ingredients.slug');
			}
			$ingredientArray=$ingredientArray->get();
        }
        
		return $ingredientArray;

		return ['data'=>[$query[0],'category'=>$categoryArray[0]]];
	}

    function GetValidTags($allTags){
		
		
		$tags=$allTags[0];
		$validTags=[0];
		
																	
		for($i=0; $i < count($tags);$i++){
	
		$element = Tag::where('title',[ucfirst($tags[$i])])->select('id')->get();
			if($element != '[]'){
				$num=(int)(filter_var($element, FILTER_SANITIZE_NUMBER_INT));
				array_push($validTags, $num);
			}
		}
		array_splice($validTags, 0, 1);
		return $validTags;
	}


    function GetValidWith($allWith){
		
		
		$with=$allWith[0];
		$validWith=[0];
		
																	
		for($i=0; $i < count($with);$i++){
	
		    if(str_contains(strtolower($with[$i]),'categor')){
				
				array_push($validWith, 'category');
			}
            if(str_contains(strtolower($with[$i]),'tag')){
				
				array_push($validWith, 'tag');
			}
            if(str_contains(strtolower($with[$i]),'ingredient')){
				
				array_push($validWith, 'ingredient');
			}
		}
		array_splice($validWith, 0, 1);
		return $validWith;
	}


	function GetFoodId($food){
		
		$validId=[0];
		
		foreach($food as $element){
			array_push($validId, $element->id);
		}															
		
		array_splice($validId, 0, 1);
		return $validId;
	}
}