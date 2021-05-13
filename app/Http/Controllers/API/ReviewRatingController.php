<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\ListingReview; 

class ReviewRatingController extends Controller
{
	public $successStatus = 200;
	public function reviewrating(Request $request)
	{
		try
		{
		 $validator = Validator::make($request->all(), [ 
	            'review' => 'required',
	            'rating' => 'required',  
	        ]);

			if ($validator->fails())
			   { 
	             return response()->json(['errors'=>$validator->errors()], $this->successStatus);
			    }
			$input = $request->all();
			$user=$input['user_id'];
			$user_alreday_exist=ListingReview::where('user_id',$user)->first();
			if($user_alreday_exist)
			{
             return response()->json(['success' => false,
	            					 'message' => "Review already given",
	            					], $this->successStatus);
            
			}
			$input['user_id'] = $input['user_id'];
			$input['review'] = $input['review'];
			$input['rating'] = $input['rating'];
			$listingreview = ListingReview::create($input); 
			return response()->json(['success' => true,
	            					 'listingreview' => $listingreview,
	            					], $this->successStatus);
		}
		catch(\Exception $e){
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
	}
    
}
