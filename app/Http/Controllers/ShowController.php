<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Tag;
use App\Models\Language;


class ShowController extends Controller
{
	function Form(){
		
		$data_category = Category::all();
		$data_language=Language::all();
		
		return view('index', [ 'category' => $data_category, 'language'=>$data_language]);
	}
	
	function Show(Request $request){
		
		
																	//DOHVACANJE REQUEST
		$per_page= $request->input('per_page');
		
		$page = $request->input('page');
		
		$category = $request->input('category');
		
		$with = $request->input('with');
		
		$lang = $request->input('lang');
		
		$diff_time = $request->input('diff_time');
		
		$allTags[] = explode(',',str_replace(' ', '', $request->input('tags')));
		
		
		if(isset($per_page))									//ispravnost per_page
		
			if( !is_numeric($per_page))
				
				return view('error', [ 'error' => 'Dishes per page atributes must be a number']);
		
		if(isset($page))										//ispravnost page
		
			if( !is_numeric($page))
				
				return view('error', [ 'error' => 'Page atributes must be a number']);
		


		
		return Food::only(['1','2']); //////////////////////////////////TEST

		switch($with)
		{
			case "ingredients":				
			{
				
				if($lang== "-"){
					return app('App\Http\Controllers\ShowFoodWithIngredientController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
				}
				else{
					return app('App\Http\Controllers\ShowFoodWithIngredientTranslatedController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
				}
				
				break;
			}
			case "categories":
			{
				if($lang== "-"){
					return app('App\Http\Controllers\ShowFoodWithCategoryController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
				}
				else{
					return app('App\Http\Controllers\ShowFoodWithCategoryTranslatedController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
				}
				
				break;;
			}
			case  "tags":
			{
				
				if($lang== "-"){
					return app('App\Http\Controllers\ShowFoodWithTagsController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
				}
				else{
					return app('App\Http\Controllers\ShowFoodWithTagsTranslatedController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
				}
				
				break;
			}
			
			case  "ingredients,tags":
			{
				if($lang== "-"){
					return app('App\Http\Controllers\ShowFoodWithIngredientTagsController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
				}
				else{
					return app('App\Http\Controllers\ShowFoodWithIngredientTagsTranslatedController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
				}
				
				break;
			}
			
			case "ingredients,categories":
			{
				if($lang== "-"){
					return app('App\Http\Controllers\ShowFoodWithIngredientCategoryController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
				}
				else{
					return app('App\Http\Controllers\ShowFoodWithIngredientCategoryTranslatedController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
				}
				
				break;
			}
			
			case "ingredients,categories,tags":{
				
				if($lang== "-"){
					return app('App\Http\Controllers\ShowFoodWithTagsIngredientCategoryController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
				}
				else{
					return app('App\Http\Controllers\ShowFoodWithTagsIngredientCategoryTranslatedController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
				}
				
				break;
			}
			
			default:
			{
				
				if($lang== "-"){
					
					return app('App\Http\Controllers\ShowFoodController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
				}
				else{
					
					return app('App\Http\Controllers\ShowFoodTranslatedController')->ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags);
		
				}
				
				
			}//default
		}//switch
	}//func
}