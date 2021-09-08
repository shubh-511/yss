<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\ListingReview; 
use App\Booking;
use App\CallLog;
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

			if(!empty($review_data)){ 
				$this->updateListingAvgRating($request->listing_id);
			}

			return response()->json(['success' => true,
	            					 'listingreview' => $review_data,
	            					], $this->successStatus);
		}
		catch(\Exception $e){
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
	}

	public function updateReview(Request $request)
	{
		try
		{
			$validator = Validator::make($request->all(), [ 
	            'review_id' => 'required',
	            'rating' => 'required'
		    ]);
	        if ($validator->fails())
			{ 
		        return response()->json(['errors'=>$validator->errors()], $this->successStatus);
			}

			$user = Auth::user();
			$checkReview = ListingReview::where('user_id', $user->id)->where('id', $request->review_id)->first();
			if(!empty($checkReview))
			{
				$checkReview->review = $request->review;
				$checkReview->rating = $request->rating;
				$checkReview->save(); 

				if(!empty($checkReview)){
					$this->updateListingAvgRating($checkReview->listing_id);
				}

				return response()->json(['success' => true,
	            					 'listingreview' => $checkReview,
	            					], $this->successStatus);
			}
			else
			{
				return response()->json(['success' => false,
				 						'message' => "Invalid review id",
										], $this->successStatus);
			}			
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
				$chackIfCallDone = 0;
				$checkIfBooked = Booking::where('user_id', $user->id)->where('counsellor_id', $listingCounsellor->user_id)->get();
				if(count($checkIfBooked) > 0)
				{
					foreach($checkIfBooked as $checkIfPackageBooked)
					{
						$bookingIds[] = $checkIfPackageBooked->id;
					}

					$chackIfCallDone = CallLog::whereIn('booking_id', $bookingIds)->where('call_duration','>',0)->first();
				}

				$checkIfAlreadyReviewed=ListingReview::where('user_id',$user->id)->where('listing_id',$request->listing_id)->first();

				$allowToReview = (!empty($chackIfCallDone)) ? 1 : 0;
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
    
    public function updateListingAvgRating($listingId){
    	try{

    		$listing = Listing::find($listingId);
			$listingAvgRating = ListingReview::where('listing_id',$listingId)->avg('rating');
			$listing->avg_rating = $listingAvgRating;
			$listing->save();

    	}catch(\Exception $e){
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	}
    }
}
