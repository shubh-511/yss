<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\ListingReview; 
use Illuminate\Support\Facades\Auth;

class ReviewRatingController extends Controller
{
	public $successStatus = 200;
	public function reviewrating(Request $request)
	{
		try
		{
		    $validator = Validator::make($request->all(), [ 
	            'rating' => 'required',
	            'listing_id' =>'required', 
	        ]);
        	if ($validator->fails())
		       { 
                 return response()->json(['errors'=>$validator->errors()], $this->successStatus);
                }
			$user = Auth::user();
			$user_alreday_exist=ListingReview::where('user_id',$user->id)->where('listing_id',$request->listing_id)->first();
			if($user_alreday_exist)
			{
                return response()->json(['success' => false,
	            					 'message' => "Review already given",
	            					], $this->successStatus);
            
			}
            $review_data=new ListingReview();
			$review_data->user_id =$user->id;
			$review_data->review = $request->review;
			$review_data->rating = $request->rating;
			$review_data->listing_id=$request->listing_id;
			$review_data->save(); 
			return response()->json(['success' => true,
	            					 'listingreview' => $review_data,
	            					], $this->successStatus);
		}
		catch(\Exception $e){
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
	}
    
}
