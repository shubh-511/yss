<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\ListingCategory;
use App\ListingLabel;
use JWTAuth;
use JWT;
use App\User;
use App\ListingRegion;
use App\ListingGallery;
use App\multilabel;
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

            $requestedFields = $request->params;
            //$requestedFields = json_decode($requestedFields, true);
            

            $rules = $this->validateData($requestedFields);
            
            $validator = Validator::make($requestedFields, $rules);
            /*$validator = Validator::make($request->all(), [ 
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
            ]);*/

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
            }

            //$input = $request->all(); 
            $user = new User;
            $user->name = strtolower($requestedFields['name']);
            $user->email = strtolower($requestedFields['email']);
            $user->password = bcrypt($requestedFields['password']);
            $user->role_id = 2;
            $user->timezone = $requestedFields['timezone'];
            $user->account_enabled = '1';

            if(!empty($requestedFields['avatar_id']))
            {
                $avtarImage = $this->createImage($requestedFields['avatar_id']);
                $user->avatar_id = $avtarImage;
            }

            if(!empty($requestedFields['cover_img']))
            {
                $coverImage = $this->createImage($requestedFields['cover_img']);
                $user->cover_id = $coverImage;
            }
            
            $user->save();

            $listingData = new Listing;
            $listingData->user_id = $user->id;
            $listingData->listing_name = $requestedFields['listing_name'];
            $listingData->location = $requestedFields['location'];
            $listingData->contact_email_or_url = $requestedFields['contact_email_or_url'];
            $listingData->description = $requestedFields['description'];
            $listingData->listing_category = $requestedFields['listing_category'];
            $listingData->lattitude = $requestedFields['lattitude'];
            $listingData->longitude = $requestedFields['longitude'];
            $listingData->listing_region = $requestedFields['listing_region'];
            //$listingData->listing_label = $requestedFields['listing_label'];
            $listingData->website = $requestedFields['website'];
            $listingData->phone = $requestedFields['phone'];
            $listingData->video_url = $requestedFields['video_url'];
            $listingData->status = "0";
            if(!empty($requestedFields['cover_img']))
            {
                $listingData->cover_img = $user->cover_id;
            }
            $listingData->save();


            if(count($requestedFields['gallery_images']) > 0)
            {
                foreach($requestedFields['gallery_images'] as $galleryImages)
                {
                    $galleryimg = new ListingGallery;
                    $galleryimg->listing_id = $listingData->id;
                    $galleryimg->gallery_img = $this->createImage($galleryImages);
                    $galleryimg->save();
                }
            }

            if(!empty($requestedFields['listing_label']) && count($requestedFields['listing_label']) > 0)
            {
                foreach($requestedFields['listing_label'] as $label_id) {
                multilabel::create([
                'listing_id' => $listingData->id,
                'label_id' => $label_id
                    ]);
                    }
            }
            
            $insertedListingData = Listing::with('gallery','listing_category','listing_label','listing_region')->where('status', '1')->where('id', $listingData->id)->first();

            $userData = User::with('roles')->where('id', $user->id)->first();

            $token = JWTAuth::fromUser($userData);

            return response()->json(['success' => true,
                         'user_data' => $userData,
                         'listing_data' => $insertedListingData,
                         'token' => $token
                        ], $this->successStatus); 

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
    }

    /** 
     * Update Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function updateListing(Request $request) 
    {
        try
        {

            $requestedFields = $request->params;
            //$requestedFields = json_decode($requestedFields, true);

            $rules = $this->validateUpdateListingData($requestedFields);
            
            $validator = Validator::make($requestedFields, $rules);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
            }

            $listingData = Listing::where('user_id', Auth::user()->id)->where('status', '1')->first();
            $listingData->listing_name = $requestedFields['listing_name'];
            $listingData->location = $requestedFields['location'];
            $listingData->contact_email_or_url = $requestedFields['contact_email_or_url'];
            $listingData->description = $requestedFields['description'];
            $listingData->listing_category = $requestedFields['listing_category'];
            $listingData->lattitude = $requestedFields['lattitude'];
            $listingData->longitude = $requestedFields['longitude'];
            $listingData->listing_region = $requestedFields['listing_region'];
            //$listingData->listing_label = $requestedFields['listing_label'];
            $listingData->website = $requestedFields['website'];
            $listingData->phone = $requestedFields['phone'];
            $listingData->video_url = $requestedFields['video_url'];
            if(!empty($requestedFields['cover_image']))
            {
                $coverImage = $this->createImage($requestedFields['cover_image']);
                $listingData->cover_img = $coverImage;
            }
            $listingData->save();


            if(!empty($requestedFields['gallery_images']) && count($requestedFields['gallery_images']) > 0)
            {
                foreach($requestedFields['gallery_images'] as $galleryImages)
                {
                    $galleryimg = new ListingGallery;
                    $galleryimg->listing_id = $listingData->id;
                    $galleryimg->gallery_img = $this->createImage($galleryImages);
                    $galleryimg->save();
                }
            }

            multilabel::where('listing_id', $listingData->id)->delete();

            if(!empty($requestedFields['listing_label']) && count($requestedFields['listing_label']) > 0)
            {
                foreach($requestedFields['listing_label'] as $label_id) {
                multilabel::create([
                'listing_id' => $listingData->id,
                'label_id' => $label_id
                    ]);
                    }
            }
            $allSelectedLabels = multilabel::where('listing_id', $listingData->id)->get();


            $insertedListingData = Listing::with('gallery','listing_category','listing_region')->where('status', '1')->where('id', $listingData->id)->first();
            if(count($allSelectedLabels) > 0)
            {
                $allSelectedLabels = $allSelectedLabels->pluck('label_id');
                $getSelectedLabels = ListingLabel::whereIn('id', $allSelectedLabels)->get();
                $insertedListingData->multilabel = $getSelectedLabels;
            }
            else
            {
                $insertedListingData->multilabel = [];
            }

            return response()->json(['success' => true,
                        'message' => 'Listing updated',
                        'listing_data' => $insertedListingData
                        ], $this->successStatus); 

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
    }

    /** 
     * Get Listing Detail By ID
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getListingById($listingId) 
    { 
        try
        {
            //$user = Auth::user();
            $listingData = Listing::with('gallery','listing_category','listing_label','listing_region')->with('user:id,avatar_id,email,profile_percentage,name')->where('id', $listingId)->where('status', '1')->first();
            $listing_label =multilabel::where('listing_id',$listingData->id)->get();
            if(count($listing_label) > 0)
            {
                $listing_label = $listing_label->pluck('label_id');
                $listingLabel = ListingLabel::whereIn('id', $listing_label)->get();
                $listingData->multilabel = $listingLabel;
            }
            else
            {
                $listingData->multilabel = [];   
            }
            
            if(!empty($listingData))
            {
                return response()->json(['success' => true,
                                        //'profile_percentage' => $user->profile_percentage,
                                        'data' => $listingData
                                        ], $this->successStatus);
            }
            else
            {
                return response()->json(['success' => false,
                                     'errors' => [ 'exception' => 'Invalid listing Id'],
                                    ], $this->successStatus);
            }
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 

    }

    /** 
     * Delete Listing Gallery Image By ID
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function deleteListingGallery($imageId) 
    { 
        try
        {
            $user = Auth::user();
            $image = ListingGallery::where('id', $imageId)->first();
            
            if(!empty($image))
            {
                $status = ListingGallery::where('id', $imageId)->delete();
                if($status == 1)
                {
                    return response()->json(['success' => true,
                                        'message' => 'Deleted successfully'
                                        ], $this->successStatus);
                }
                else
                {
                    return response()->json(['success' => false,
                                     'errors' => [ 'exception' => 'Invalid image Id'],
                                    ], $this->successStatus);
                }
                
            }
            else
            {
                return response()->json(['success' => false,
                                     'errors' => [ 'exception' => 'Invalid image Id'],
                                    ], $this->successStatus);
            }
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 

    }

    /** 
     * Search Listing
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function searchListing(Request $request) 
    { 
        try
        {
            if(!empty($request->all_records))
            {
                $listingData = Listing::with('gallery','listing_category','listing_label','listing_region','user')->where('status', '1')->orderBy('id', 'DESC')->paginate(8);
                
            }
            elseif(!empty($request->lattitude) && !empty($request->longitude))
            {
                $validator = Validator::make($request->all(), [ 
                'listing_category' => 'required'
                ]);

                if ($validator->fails()) 
                { 
                    return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
                }
                $listingData = Listing::with('gallery','listing_category','listing_label','listing_region','user')->where('status', '1')->where('listing_category', $request->listing_category)->where('lattitude', $request->lattitude)->where('longitude', $request->longitude)->orderBy('id', 'DESC')->paginate(8);
            }
            else
            {
                $validator = Validator::make($request->all(), [ 
                'listing_category' => 'required'
                ]);

                if ($validator->fails()) 
                { 
                    return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
                }
                $listingData = $this->getSortedListingData($request->sort_by, $request->listing_category);
            }
            
            if(count($listingData) > 0)
            {
                return response()->json(['success' => true,
                                      'data' => $listingData,
                                    ], $this->successStatus);
            }
            else
            {
                return response()->json(['success' => false,
                                     'errors' => [ 'exception' => 'No listing found'],
                                    ], $this->successStatus);
            }
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
    public function getSortedListingData($sortBy, $listingCategory) 
    { 
        switch ($sortBy) {
        case 1:
            $listingData = Listing::with('gallery','listing_category','listing_label','listing_region','user')->where('status', '1')->where('listing_category', $listingCategory)->orderBy('id', 'DESC')->paginate(8);
        break;
        case 2:
            $listingData = Listing::with('gallery','listing_category','listing_label','listing_region','user')->where('status', '1')->where('listing_category', $listingCategory)->orderBy('id', 'ASC')->paginate(8);
        break;
        case 3:
            $listingData = Listing::with('gallery','listing_category','listing_label','listing_region','user')->where('status', '1')->where('listing_category', $listingCategory)->orderBy('id', 'DESC')->paginate(8);
        break;
        case 4:
            $listingData = Listing::with('gallery','listing_category','listing_label','listing_region','user')->where('status', '1')->where('listing_category', $listingCategory)->orderBy('id', 'DESC')->paginate(8);
        break;
        case 5:
            $listingData = Listing::with('gallery','listing_category','listing_label','listing_region','user')->where('status', '1')->where('listing_category', $listingCategory)->orderBy('id', 'DESC')->paginate(8);
        break;
        default:
            $listingData = Listing::with('gallery','listing_category','listing_label','listing_region','user')->where('status', '1')->where('listing_category', $listingCategory)->orderBy('id', 'DESC')->paginate(8);
        }

        return $listingData;
    }

    /*
     * Validate Data
     * @Params $requestedfields
     */

    public function validateData($requestedFields){
        $rules = [];
        foreach ($requestedFields as $key => $field) 
        {
            if($key == 'name')
            {
                $rules[$key] = 'required|max:190';
            }
            else if($key == 'email')
            {
                $rules[$key] = 'required|max:190|email|unique:users';
            }
            else if($key == 'password')
            {
                $rules[$key] = 'required';
            }
            else if($key == 'c_password')
            {
                $rules[$key] = 'required|same:password';
            }
            else if($key == 'timezone')
            {
                $rules[$key] = 'required';
            }
            else if($key == 'listing_name')
            {
                $rules[$key] = 'required|max:190';
            }
            else if($key == 'location')
            {
                $rules[$key] = 'required|max:190';
            }
            /*else if($key == 'contact_email_or_url')
            {
                $rules[$key] = 'required|max:190';
            }*/
            else if($key == 'description')
            {
                $rules[$key] = 'required';
            }
            else if($key == 'listing_category')
            {
                $rules[$key] = 'required';
            }
            else if($key == 'listing_region')
            {
                $rules[$key] = 'required';
            }
            else if($key == 'website')
            {
                $rules[$key] = 'required';
            }
            /*else if($key == 'phone')
            {
                $rules[$key] = 'required';
            }*/
            /*else if($key == 'video_url')
            {
                $rules[$key] = 'required';
            }*/
        }

        return $rules;

    }

    /*
     * Validate Update Listing Data
     * @Params $requestedfields
     */

    public function validateUpdateListingData($requestedFields){
        $rules = [];
        foreach ($requestedFields as $key => $field) 
        {
            if($key == 'listing_name')
            {
                $rules[$key] = 'required|max:190';
            }
            else if($key == 'location')
            {
                $rules[$key] = 'required|max:190';
            }
            /*else if($key == 'contact_email_or_url')
            {
                $rules[$key] = 'required|max:190';
            }*/
            else if($key == 'description')
            {
                $rules[$key] = 'required';
            }
            else if($key == 'listing_category')
            {
                $rules[$key] = 'required';
            }
            else if($key == 'listing_region')
            {
                $rules[$key] = 'required';
            }
            else if($key == 'website')
            {
                $rules[$key] = 'required';
            }
            /*else if($key == 'phone')
            {
                $rules[$key] = 'required';
            }*/
            /*else if($key == 'video_url')
            {
                $rules[$key] = 'required';
            }*/
        }

        return $rules;

    }

    /** 
     * Create Image From Base64 string
     * 
     * Pamameters $img
     */ 
    public function createImage($img)
    {
        $folderPath = "uploads/";
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = end($image_type_aux);
        $img = str_replace("data:image/".$image_type.";base64,", '', $img);
        $img = str_replace(' ', '+', $img);
        $image_base64 = base64_decode($img);
        $file = $folderPath . uniqid() . ".".$image_type;
        file_put_contents($file, $image_base64);
        return $file;
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
                                      'labels' => $listingLabelData,
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
            $listingRegionData = ListingRegion::where('status', '1')->get();
            if(count($listingRegionData) > 0)
            {
                return response()->json(['success' => true,
                                      'regions' => $listingRegionData,
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
