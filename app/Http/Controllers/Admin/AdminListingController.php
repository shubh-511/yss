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
use App\User;
use Carbon\Carbon;
use App\multilabel;
use App\ListingGallery;
use App\Events\UserRegisterEvent;
use App\Events\ListingEvent;
use App\Events\RejectListingEvent;
use App\Events\DisableListingEvent;
use App\GeneralSetting;
use App\Traits\CheckPermission;
use App\Notification;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class AdminListingController extends Controller
{
    use CheckPermission;
     public $successStatus = 200;
	

    /** 
     * Get all listings
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getListings(Request $request) 
    {
         $module_name=$this->permission(Auth::user()->id);
         $general_setting= GeneralSetting::where('id','=',1)->first();
         if ($request->get('email') != null && $request->get('listing_name') != null) 
          {
                $listings = Listing::with('user')->whereHas('user', function ($query) use ($request)
                {
                 $query->where('email', 'LIKE', '%' . $request->get('email') . '%');
                })->whereHas('user', function ($query) use ($request)
               {
                 $query->where('listing_name', 'LIKE', '%' . $request->get('listing_name') . '%');
                })->orderBy('id','DESC')->paginate($general_setting->pagination_value);
                 return view('admin.listings.index',compact('listings','module_name'))
                ->with('i', ($request->input('page', 1) - 1) * 5);
            }
          if ($request->get('email') != null) 
          {
              $listings = Listing::with('user')->whereHas('user', function ($query) use ($request)
              {
                $query->where('email', 'LIKE', '%' . $request->get('email') . '%');
                 })->orderBy('id','DESC')->paginate($general_setting->pagination_value);
               return view('admin.listings.index',compact('listings','module_name'))
                ->with('i', ($request->input('page', 1) - 1) * 5);
            }

        	$listings = Listing::where(function ($query) use($request) {
                 if ($request->get('listing_name') != null)
                  {
                    $query->where('listing_name', 'like', '%' . $request->get('listing_name') . '%');
                   }
                 })->orderBy('id','DESC')->paginate($general_setting->pagination_value);
               return view('admin.listings.index',compact('listings','module_name'))
                ->with('i', ($request->input('page', 1) - 1) * 5);
        }

    /** 
     * Get listings details
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getListingDetails($listingId='') 
    {
        $module_name=$this->permission(Auth::user()->id);
        $listing = Listing::with('user','listing_category','listing_region')->where('id',$listingId)->first();
        $gallery_data=ListingGallery::where('listing_id',$listingId)->get();
        $label_data=multilabel::where('listing_id',$listingId)->get();
        return view('admin.listings.detail',compact('listing','label_data','gallery_data','module_name'));
        
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
        $user = User::where('id', $model->user_id)->first();
        if($model->status==1)
        {
            event(new ListingEvent($model->user_id));
            if($model) 
            {
                $model->status = $status;
                $model->save();
                $newNotif = new Notification;
                $newNotif->receiver = $model->user_id;
                $newNotif->title = "Admin enable your listing";
                $newNotif->body = "Listing enable by admin";
                $time=Carbon::now($user->timezone)->toDateTimeString();
                $newNotif->created_at=$time;
                $newNotif->save();
            }
        }
          else
          {
            event(new DisableListingEvent($model->user_id));
            $model->status = $status;
            $model->save();
            $newNotif = new Notification;
            $newNotif->receiver = $model->user_id;
            $newNotif->title = "Admin disable your listing";
            $newNotif->body = "Listing disable by admin";
            $time=Carbon::now($user->timezone)->toDateTimeString();
            $newNotif->created_at=$time;
            $newNotif->save();
          }
    }
    public function rejectListingStatus()
    {
        $id=$_GET['id'];
        $status=$_GET['value'];
        $msg=$_GET['msg'];
        $model = Listing::find($id);
        $user = User::where('id', $model->user_id)->first();
        $model->status = $status;
        $model->save();
        event(new RejectListingEvent($model->user_id,$msg));
        $newNotif = new Notification;
        $newNotif->receiver = $model->user_id;
        $newNotif->title = "Admin reject your listing";
        $newNotif->body = "Listing reject by admin";
        $time=Carbon::now($user->timezone)->toDateTimeString();
        $newNotif->created_at=$time;
        $newNotif->save();

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
    public function download(Request $request)
    {
      try
      {
       $listing_name=$request->listing_name;
       $email=$request->email;
       $writer = WriterEntityFactory::createXLSXWriter(Type::XLSX);
       $writer->openToBrowser('Listing'.date('Y-m-d:hh:mm:ss').'.xlsx');
       $column = [
              WriterEntityFactory::createCell('Listing  Id'),
              WriterEntityFactory::createCell('Listing Name'),
              WriterEntityFactory::createCell('Listing Category'),
              // WriterEntityFactory::createCell('Created By'),
              WriterEntityFactory::createCell('Status'),

          ];
        $singleRow = WriterEntityFactory::createRow($column);
        $writer->addRow($singleRow);
          if($listing_name != "")
          {
              $listings=Listing::with('category')->where('listing_name', 'like', '%' . $listing_name . '%')->get();
               foreach ($listings as $key => $listing) 
                {
                  $cells = [
                  WriterEntityFactory::createCell($listing['id']),
                  WriterEntityFactory::createCell($listing['listing_name']),
                  WriterEntityFactory::createCell($listing['category']['category_name']),
                  WriterEntityFactory::createCell($listing['status']),
                ];
                $singleRow = WriterEntityFactory::createRow($cells);
                $writer->addRow($singleRow); 
               }
          }
           elseif($email != "")
          {
             $listings= Listing::with(['category', 'user' => function($q) use($value) {
               $q->where('email', 'LIKE', '%' . $email . '%');
              }])->get();
               foreach ($listings as $key => $listing) 
                {
                  $cells = [
                  WriterEntityFactory::createCell($listing['id']),
                  WriterEntityFactory::createCell($listing['listing_name']),
                  WriterEntityFactory::createCell($listing['category']['category_name']),
                  WriterEntityFactory::createCell($listing['status']),
                ];
                $singleRow = WriterEntityFactory::createRow($cells);
                $writer->addRow($singleRow); 
               }
          }
         else
         {
            $listings=Listing::with('category')->get();
            foreach ($listings as $key => $listing) 
                {
                  $cells = [
                  WriterEntityFactory::createCell($listing['id']),
                  WriterEntityFactory::createCell($listing['listing_name']),
                  WriterEntityFactory::createCell($listing['category']['category_name']),
                  WriterEntityFactory::createCell($listing['status']),
                ];
                $singleRow = WriterEntityFactory::createRow($cells);
                $writer->addRow($singleRow); 
               }
          }
          $writer->close();
          exit();
        }
        catch(\Exception $e)
        {
            return redirect()->back()->with('err_message','Something went wrong!');
        }
      }

}