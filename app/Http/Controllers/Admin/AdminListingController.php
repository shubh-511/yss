<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Booking;
use App\Listing;
use Event;
use Carbon\Carbon;
use App\Events\UserRegisterEvent;

class AdminListingController extends Controller
{
    public $successStatus = 200;
	

    /** 
     * Get all listings
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getListings(Request $request) 
    {
         if ($request->get('email') != null && $request->get('listing_name') != null) 
          {
                $listings = Listing::with('user')->whereHas('user', function ($query) use ($request)
                {
                 $query->where('email', 'LIKE', '%' . $request->get('email') . '%');
                })->whereHas('user', function ($query) use ($request)
               {
                 $query->where('listing_name', 'LIKE', '%' . $request->get('listing_name') . '%');
                })->orderBy('id','DESC')->paginate(25);
                 return view('admin.listings.index',compact('listings'))
                ->with('i', ($request->input('page', 1) - 1) * 5);
            }
          if ($request->get('email') != null) 
          {
              $listings = Listing::with('user')->whereHas('user', function ($query) use ($request)
              {
                $query->where('email', 'LIKE', '%' . $request->get('email') . '%');
                 })->orderBy('id','DESC')->paginate(25);
               return view('admin.listings.index',compact('listings'))
                ->with('i', ($request->input('page', 1) - 1) * 5);
            }

        	$listings = Listing::where(function ($query) use($request) {
                 if ($request->get('listing_name') != null)
                  {
                    $query->where('listing_name', 'like', '%' . $request->get('listing_name') . '%');
                   }
                 })->orderBy('id','DESC')->paginate(25);
               return view('admin.listings.index',compact('listings'))
                ->with('i', ($request->input('page', 1) - 1) * 5);
        }

    /** 
     * Get listings details
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getListingDetails($listingId='') 
    {
        $listing = Listing::with('user','listing_category','listing_label','listing_region')->where('id',$listingId)->first();
        return view('admin.listings.detail',compact('listing'));
        
    }

    /** 
     * update listing status
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public static function listingStatus()
    {
        $id=$_GET['id'];
        $status=$_GET['value'];
        $model = Listing::find($id);
        if($model) 
        {
            $model->status = $status;
            $model->save();
        }
    }

    

}
