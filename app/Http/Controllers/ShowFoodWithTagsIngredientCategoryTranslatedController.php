<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Tag;
use App\Models\Language;

class ShowFoodWithTagsIngredientCategoryTranslatedController extends Controller
{
    function ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags){
		
				
		$validTags= $this->GetValidTags($allTags);
			
		if(isset($diff_time)){
			
			if( !is_numeric($diff_time) || $diff_time < 0) return view('error', [ 'error' => 'Diff time atributes must be a number']);
				
			else{//diff
				
				if($category=="-"){//category
					
					if($validTags){//tags
																												//eng,diff,tags//
						$foodWithTags=Food::join('food_tag','food.id','=','food_tag.food_id')				
							->join('tags','tags.id','=','food_tag.tag_id')
							->whereIn('tags.id',$validTags)
							->join('trans_foods','food.id','=','trans_foods.food_id')
							->where('language_id',$lang)
							->select('food.id','trans_foods.title','trans_foods.description')->get();
							
						return $this->CheckPaginate($this->GetString($foodWithTags,$lang),$per_page,$page);
						
					}else{//no tags
						
																												//eng,diff//
						$allFood=Food::query()
							->join('trans_foods','food.id','=','trans_foods.food_id')
							->where('language_id',$lang)
							->select('food.id','trans_foods.title','trans_foods.description')->get();																	
						
						return $this->CheckPaginate($this->GetString($allFood,$lang),$per_page,$page);
						
					}
				}else{//no category
					
					if($validTags){
																												//eng,diff,category,tags//
						$foodWithTags=Food::where('category_id',$category)										
							->join('food_tag','food.id','=','food_tag.food_id')
							->join('tags','tags.id','=','food_tag.tag_id')
							->whereIn('tags.id',$validTags)
							->join('trans_foods','food.id','=','trans_foods.food_id')
							->where('language_id',$lang)
							->select('food.id','trans_foods.title','trans_foods.description')->get();
							
							return $this->CheckPaginate($this->GetString($foodWithTags,$lang),$per_page,$page);
							
					}else{		
																												//eng,diff,category//
						$foodWithCategory = Food::where('category_id',$category)
							->join('trans_foods','food.id','=','trans_foods.food_id')
							->where('language_id',$lang)
							->select('food.id','trans_foods.title','trans_foods.description')->get();								
						
						return $this->CheckPaginate($this->GetString($foodWithCategory,$lang),$per_page,$page);
					}	
				}
			}
		}
		else{// no diff
		
			if($category=="-"){//category
					
					if($validTags){//tags
																												//eng,tags//
						$foodWithTags=Food::join('food_tag','food.id','=','food_tag.food_id')				
							->where('status','created')
							->join('tags','tags.id','=','food_tag.tag_id')
							->whereIn('tags.id',$validTags)
							->join('trans_foods','food.id','=','trans_foods.food_id')
							->where('language_id',$lang)
							->select('food.id','trans_foods.title','trans_foods.description')->get();
							
							
							return $this->CheckPaginate($this->GetString($foodWithTags,$lang),$per_page,$page);
						
					}else{//no tags
																												//eng//			
						$allFood=Food::where('status','created')
							->join('trans_foods','food.id','=','trans_foods.food_id')
							->where('language_id',$lang)
							->select('food.id','trans_foods.title','trans_foods.description')->get();	
						
						return $this->CheckPaginate($this->GetString($allFood,$lang),$per_page,$page);
						
					}
				}else{//no category
					
					if($validTags){
																												//eng,category,tags//
						$foodWithTags=Food::where('category_id',$category)										
							->leftJoin('food_tag','food.id','=','food_tag.food_id')
							->where('status','created')
							->join('tags','tags.id','=','food_tag.tag_id')
							->whereIn('tags.id',$validTags)
							->join('trans_foods','food.id','=','trans_foods.food_id')
							->where('language_id',$lang)
							->select('food.id','trans_foods.title','trans_foods.description')->get();
							
							return $this->CheckPaginate($this->GetString($foodWithTags,$lang),$per_page,$page);
							
					}else{		
																												//eng,category//
						$foodWithCategory = Food::where('category_id',$category)->where('status','created')
							->join('trans_foods','food.id','=','trans_foods.food_id')
							->where('language_id',$lang)
							->select('food.id','trans_foods.title','trans_foods.description')->get();	
						
						return $this->CheckPaginate($this->GetString($foodWithCategory,$lang),$per_page,$page);
					}
					
				}
			
		
		}	
	}
	
	function CheckPaginate($query,$per_page,$page){
		
		if(isset($per_page))
			return view('tagIngredientCategoryPage',['all'=>[\Illuminate\Http\JsonResponse::create(\App\Http\Controllers\CostumPaginateController::paginate(collect($query),$per_page))]]);
		else
			return view('tagIngredientCategoryPage',['all'=>['data'=>$query]]);
	}
	
	
	function GetValidTags($allTags){
		
		
		$tags=$allTags[0];
		$validTags=[0];
		
																	//dobiti validne tag-ove (id)
		for($i=0;$i < count($tags);$i++){
	
		$element = Tag::where('title',[ucfirst($tags[$i])])->select('id')->get();
			if($element != '[]'){
				$num=(int)(filter_var($element, FILTER_SANITIZE_NUMBER_INT));
				array_push($validTags, $num);
			}
		}
		array_splice($validTags, 0, 1);
		return $validTags;
	}
	
	function GetValidFoodId($food){
		
		
		$validId=[0];
		
																	//dobiti validne tag-ove (id)
		for($i=0;$i < count($food);$i++){
	
		$element = Tag::where('title',[ucfirst($food[$i])])->select('id')->get();
			if($element != '[]'){
				$num=(int)(filter_var($element, FILTER_SANITIZE_NUMBER_INT));
				array_push($validId, $num);
			}
		}
		array_splice($validId, 0, 1);
		return $validId;
	}
	
	
	function GetString($query,$lang){
		$string=[0];
		
		for($i =0; $i < count($query); $i++){
			
			$foodTags = Tag::join('food_tag','tags.id','=','food_tag.tag_id')
				->where('food_tag.food_id',$query[$i]->id)
				->join('trans_tags','tags.id','=','trans_tags.tags_id')
				->where('trans_tags.language_id',$lang)
				->select('tags.id','trans_tags.title')
				->get(); 
				
			$foodIngredient = Ingredient::join('food_ingredient','ingredients.id','=','food_ingredient.ingredient_id')
				->where('food_ingredient.food_id',$query[$i]->id)
				->join('trans_ingredients','ingredients.id','=','trans_ingredients.ingredient_id')
				->where('trans_ingredients.language_id',$lang)
				->select('ingredients.id','trans_ingredients.title')
				->get(); 
				
			$foodcategory = Category::join('food','categories.id','=','food.category_id')
				->where('food.id',$query[$i]->id)
				->join('trans_categories','categories.id','=','trans_categories.category_id')
				->where('trans_categories.language_id',$lang)
				->select('categories.id','trans_categories.title')
				->get();
				
			array_push($string,['title'=>$query[$i]->title,'description'=>$query[$i]->description,'tag'=>$foodTags,'category'=>$foodcategory,'ingredient'=>$foodIngredient]);
		}
		array_splice($string, 0, 1);
		
		
		return $string;
		
	}
	

}

