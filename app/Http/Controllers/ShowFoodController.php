<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Tag;
use App\Models\Language;

class ShowFoodController extends Controller
{
    function ShowFood($per_page,$page,$category,$with,$lang,$diff_time,$allTags){
		
				
		$validTags= $this->GetValidTags($allTags);
				
		if(isset($diff_time)){
			
			if( !is_numeric($diff_time) || $diff_time < 0) return view('error', [ 'error' => 'Diff time atributes must be a number']);
				
			else{//diff
				
				if($category=="-"){//category
					
					if($validTags){//tags
																												//eng,diff,tags
						$foodWithTags=Food::join('food_tag','food.id','=','food_tag.food_id')				//
							->join('tags','tags.id','=','food_tag.tag_id')
							->whereIn('tags.id',$validTags)
							->select('food.id','food.title','food.description');
							
						return $this->CheckPaginate($foodWithTags,$per_page,$page);
						
					}else{//no tags
						
																												//eng,diff
						$allFood=Food::query();																	//
						
						return $this->CheckPaginate($allFood,$per_page,$page);
						
					}
				}else{//no category
					
					if($validTags){
																												//eng,diff,category,tags
						$foodWithTags=Food::where('category_id',$category)										//
							->join('food_tag','food.id','=','food_tag.food_id')
							->join('tags','tags.id','=','food_tag.tag_id')
							->whereIn('tags.id',$validTags)
							->select('food.id','food.title','food.description');
							
							return $this->CheckPaginate($foodWithTags,$per_page,$page);
							
					}else{		
																												//eng,diff,category
						$foodWithCategory = Food::where('category_id',$category);								//
						
						return $this->CheckPaginate($foodWithCategory,$per_page,$page);
					}	
				}
			}
		}
		else{// no diff
		
			if($category=="-"){//category
					
					if($validTags){//tags
																												//eng,tags
						$foodWithTags=Food::join('food_tag','food.id','=','food_tag.food_id')				//
							->where('status','created')
							->join('tags','tags.id','=','food_tag.tag_id')
							->whereIn('tags.id',$validTags)
							->select('food.id','food.title','food.description');
														
						return $this->CheckPaginate($foodWithTags,$per_page,$page);
						
					}else{//no tags
																												//eng
						$allFood=Food::where('status','created');												//
						return $this->CheckPaginate($allFood,$per_page,$page);
						
					}
				}else{//no category
					
					if($validTags){
																												//eng,category,tags
						$foodWithTags=Food::where('category_id',$category)										//
							->leftJoin('food_tag','food.id','=','food_tag.food_id')
							->where('status','created')
							->join('tags','tags.id','=','food_tag.tag_id')
							->whereIn('tags.id',$validTags)
							->select('food.id','food.title','food.description');
							
							return $this->CheckPaginate($foodWithTags,$per_page,$page);
							
					}else{		
																												//eng,category
						$foodWithCategory = Food::where('category_id',$category)->where('status','created');	//
						
						return $this->CheckPaginate($foodWithCategory,$per_page,$page);
					}
					
				}
			
		
		}	
	}
	
	function CheckPaginate($query,$per_page,$page){
		
		if(isset($per_page))
			return view('foodPage', ['all'=>[$query->paginate($per_page)]]);
		
		else
			return view('foodPage', ['all'=>['data'=>$query->get()]]);
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
	
	

	
}
