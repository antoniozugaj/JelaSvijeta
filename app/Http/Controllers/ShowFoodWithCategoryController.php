<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Tag;
use App\Models\Language;


class ShowFoodWithCategoryController extends Controller
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
							->select('food.id','food.title','food.description')->get();
							
						return $this->CheckPaginate($this->GetString($foodWithTags),$per_page,$page);
						
					}else{//no tags
						
																												//eng,diff//
						$allFood=Food::query()->get();																	
						
						return $this->CheckPaginate($this->GetString($allFood),$per_page,$page);
						
					}
				}else{//no category
					
					if($validTags){
																												//eng,diff,category,tags//
						$foodWithTags=Food::where('category_id',$category)										
							->join('food_tag','food.id','=','food_tag.food_id')
							->join('tags','tags.id','=','food_tag.tag_id')
							->whereIn('tags.id',$validTags)
							->select('food.id','food.title','food.description')->get();
							
							return $this->CheckPaginate($this->GetString($foodWithTags),$per_page,$page);
							
					}else{		
																												//eng,diff,category//
						$foodWithCategory = Food::where('category_id',$category)->get();								
						
						return $this->CheckPaginate($this->GetString($foodWithCategory),$per_page,$page);
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
							->select('food.id','food.title','food.description')->get();
							
							
							return $this->CheckPaginate($this->GetString($foodWithTags),$per_page,$page);
						
					}else{//no tags
																												//eng//			
						$allFood=Food::where('status','created')->get();	
						
						return $this->CheckPaginate($this->GetString($allFood),$per_page,$page);
						
					}
				}else{//no category
					
					if($validTags){
																												//eng,category,tags//
						$foodWithTags=Food::where('category_id',$category)										
							->leftJoin('food_tag','food.id','=','food_tag.food_id')
							->where('status','created')
							->join('tags','tags.id','=','food_tag.tag_id')
							->whereIn('tags.id',$validTags)
							->select('food.id','food.title','food.description')->get();
							
							return $this->CheckPaginate($this->GetString($foodWithTags),$per_page,$page);
							
					}else{		
																												//eng,category//
						$foodWithCategory = Food::where('category_id',$category)->where('status','created')->get();	
						
						return $this->CheckPaginate($this->GetString($foodWithCategory),$per_page,$page);
					}
					
				}
			
		
		}	
	}
	
	function CheckPaginate($query,$per_page,$page){
		
		if(isset($per_page))
			return view('categoryPage',['all'=>[\Illuminate\Http\JsonResponse::create(\App\Http\Controllers\CostumPaginateController::paginate(collect($query),$per_page))]]);
		else
			return view('categoryPage',['all'=>['data'=>$query]]);
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
	
	
	function GetString($query){
		$string=[0];
		
		for($i =0; $i < count($query); $i++){
			
			$foodcategory = Category::join('food','categories.id','=','food.category_id')
				->where('food.id',$query[$i]->id)
				->select('categories.id','categories.title')
				->get(); 
				
		
				
			array_push($string,['title'=>$query[$i]->title,'description'=>$query[$i]->description,'category'=>$foodcategory]);
		}
		array_splice($string, 0, 1);
		
		
		return $string;
		
	}
	

}

