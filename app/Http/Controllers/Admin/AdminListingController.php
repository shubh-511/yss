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
use App\multilabel;
use App\ListingGallery;
use App\Events\UserRegisterEvent;
use App\Events\ListingEvent;

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
        $listing = Listing::with('user','listing_category','listing_region')->where('id',$listingId)->first();
        $gallery_data=ListingGallery::where('listing_id',$listingId)->get();
        $label_data=multilabel::where('listing_id',$listingId)->get();
        return view('admin.listings.detail',compact('listing','label_data','gallery_data'));
        
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
        if($model->status==0)
        {
            event(new ListingEvent($model->user_id));
            if($model) 
            {
                $model->status = $status;
                $model->save();
            }
        }
        else 
        {
             if($model) 
            {
                $model->status = $status;
                $model->save();
            }

        }
    }

    public function bulk(Request $request)
    {
       $data=$request['action'];
       $id=$request['id'];
        if($data=="enable")
       {
        $data=Listing::whereIn('id', $id)
       ->update(['status' => '1']);
       return response()->json(array('message' => 'success'));
       }
       else
       {
        $data=Listing::whereIn('id', $id)
       ->update(['status' => '0']);
       return response()->json(array('message' => 'success'));
       }
    }

}
