<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\ListingReview; 
use App\Booking;
use App\Listing;
use App\User;
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
				 'message' => "Your rating for this listing has already been submitted",
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

	public function checkRatingForListing(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [ 
	            'listing_id' =>'required', 
		    ]);
	        if ($validator->fails())
			{ 
		        return response()->json(['errors'=>$validator->errors()], $this->successStatus);
			}

			$user = Auth::user();
			$listingCounsellor = Listing::where('id', $request->listing_id)->first();
			if(!empty($listingCounsellor))
			{
				$checkIfBooked = Booking::where('user_id', $user->id)->where('counsellor_id', $listingCounsellor->user_id)->first();

				$checkIfAlreadyReviewed=ListingReview::where('user_id',$user->id)->where('listing_id',$request->listing_id)->first();

				$allowToReview = (!empty($checkIfBooked)) ? 1 : 0;
				$ratingFlag = (!empty($checkIfAlreadyReviewed)) ? 1 : 0;
				
				return response()->json(['success' => true,
	            					 'rating_flag' => $ratingFlag,
	            					 'allow_to_review' => $allowToReview,
	            					], $this->successStatus);
			}
			else
			{
				return response()->json(['success' => false,
				 						 'message' => "Invalid listing",
										], $this->successStatus);
			}
		}
		catch(\Exception $e){
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
	}

	public function gatAllReviews(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [ 
	            'listing_id' =>'required', 
		    ]);
	        if ($validator->fails())
			{ 
		        return response()->json(['errors'=>$validator->errors()], $this->successStatus);
			}

			$getAllReviews = ListingReview::with('user:id,name,email,avatar_id')->where('listing_id',$request->listing_id)->paginate(8);
			
			return response()->json(['success' => true,
	            					 'reviews' => $getAllReviews
	            					], $this->successStatus);
		}
		catch(\Exception $e){
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
	}
    
}
