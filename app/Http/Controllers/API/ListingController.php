<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\ListingCategory;
use App\ListingLabel;
use App\User;
use App\ListingRegion;
use App\Listing;
use Event;
use Carbon\Carbon;
use App\Events\UserRegisterEvent;

class ListingController extends Controller
{
    public $successStatus = 200;
	

    /** 
     * Create Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function createListing(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'name' => 'required|max:190',  
                'email' => 'required|max:190|email|unique:users', 
                'password' => 'required', 
                'c_password' => 'required|same:password', 
                'timezone' => 'required', 
                'listing_name' => 'required|max:190',
                'location' => 'required|max:190',
                'contact_email_or_url' => 'required|max:190',
                'description' => 'required',
                'listing_category' => 'required',
                'listing_region' => 'required',
                //'listing_label' => 'required',
                'website' => 'required',
                'phone' => 'required',
                'video_url' => 'required',
            ],
            [   
                'email.unique'  =>  'This email is already been registered'
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
            }

            $input = $request->all(); 
            $input['name'] = strtolower($input['name']);
            $input['email'] = strtolower($input['email']);
            $input['password'] = bcrypt($input['password']); 
            $input['role_id'] = 2;
            $input['timezone'] = $input['timezone'];
            $input['account_enabled'] = '3'; // Not verified user
            $user = User::create($input); 

            $listingData = new Listing;
            $listingData->listing_name = $request->listing_name;
            $listingData->location = $request->location;
            $listingData->contact_email_or_url = $request->contact_email_or_url;
            $listingData->description = $request->description;
            $listingData->listing_category = $request->listing_category;
            $listingData->listing_region = $request->listing_region;
            $listingData->listing_label = $request->listing_label;
            $listingData->website = $request->website;
            $listingData->phone = $request->phone;
            $listingData->video_url = $request->video_url;
            $listingData->save();

            $insertedListingData = Listing::with('listing_category','listing_label','listing_region')->where('id', $listingData->id)->first();

            return response()->json(['success' => true,
                         'user_data' => $user,
                         'listing_data' => $insertedListingData
                        ], $this->successStatus); 

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
    }

    /** 
     * Get Listing Categories
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function listingCategories() 
    { 
        try
        {
            $listingCategoryData = ListingCategory::where('status', '1')->get();
            if(count($listingCategoryData) > 0)
            {
                return response()->json(['success' => true,
                                      'categories' => $listingCategoryData,
                                    ], $this->successStatus);
            }
            else
            {
                return response()->json(['success' => false,
                                     'errors' => [ 'exception' => 'No listing categories found'],
                                    ], $this->successStatus);
            }
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 

    }

    /** 
     * Get Listing Labels
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function listingLabels() 
    { 
        try
        {
            $listingLabelData = ListingLabel::where('status', '1')->get();
            if(count($listingLabelData) > 0)
            {
                return response()->json(['success' => true,
                                      'categories' => $listingLabelData,
                                    ], $this->successStatus);
            }
            else
            {
                return response()->json(['success' => false,
                                     'errors' => [ 'exception' => 'No listing labels found'],
                                    ], $this->successStatus);
            }
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 

    }

    /** 
     * Get Listing Regions
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function listingRegion() 
    { 
        try
        {
            $listingRegionData = ListingLabel::where('status', '1')->get();
            if(count($listingRegionData) > 0)
            {
                return response()->json(['success' => true,
                                      'categories' => $listingRegionData,
                                    ], $this->successStatus);
            }
            else
            {
                return response()->json(['success' => false,
                                     'errors' => [ 'exception' => 'No listing regions found'],
                                    ], $this->successStatus);
            }
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 

    }

}
