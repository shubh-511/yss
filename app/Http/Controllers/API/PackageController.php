<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\User; 
use App\AvailaibleHours;
use App\Booking;
use App\Listing;
use App\Availability;
use Event;
use Carbon\Carbon;
use DateTime;
use App\Events\UserRegisterEvent;
use App\Traits\ProfileStatusTrait;

class PackageController extends Controller
{
    use ProfileStatusTrait;
    public $successStatus = 200;
	

    /** 
     * Create Package api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function createPackage(Request $request) 
    {
    	try
        {
            $exits = Package::where('user_id','=',Auth::user()->id)->where('package_name', $request->package_name)->count();
            if ($exits > 0)
            {
                return response()->json(['success'=>false,'errors' =>['exception' => ['Package name already exist']]], $this->successStatus); 
            }
            else
            {
        		$validator = Validator::make($request->all(), [ 
    	            'package_name' => 'required|max:190',  
    	            'package_description' => 'required', 
    	            'session_minutes' => 'required', 
                    'session_hours' => 'required', 
                    'no_of_slots'   =>  'required',
                    'amount' => 'required',
    	        ]);

    			if ($validator->fails()) 
                { 
    	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
    			}
                $user = Auth::user()->id;

    			$input = $request->all(); 
                $input['user_id'] = $user;
    	        $package = Package::create($input); 

                $profilePercentage = $this->profileStatus(Auth::user()->id);
                $userData = User::where('id', Auth::user()->id)->first();

    	        return response()->json(['success' => true,
    	            					 'package' => $package,
                                         'user' =>  $userData
    	            					], $this->successStatus); 
            }

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

    /** 
     * Get Package api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getPackagesByCounsellorId(Request $request) 
    {
        try
        {
            $user = Auth::user()->id;
            /*$validator = Validator::make($request->all(), [ 
                'user_id' => 'required',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }*/
            $allPackages = Package::where('user_id', $user)->paginate(6); 
            //$allPackages = Package::where('user_id', $user)->get(); 

            if(count($allPackages) > 0)
            {
                return response()->json(['success' => true,
                                     'packages' => $allPackages,
                                    ], $this->successStatus); 
            }
            else
            {
                /*return response()->json(['success' => false,
                                     'message' => 'No packages found',
                                    ], $this->successStatus); */

                return response()->json(['success'=>false,'errors' =>['exception' => ['No packages found']]], $this->successStatus); 
            }
            

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }

    /** 
     * View Package api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function viewPackage(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'package_id' => 'required',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }

            $package = Package::where('id', $request->package_id)->first();
            if(!empty($package))
            {
                return response()->json(['success' => true,
                                     'package' => $package,
                                    ], $this->successStatus);
            }
            else
            {
                /*return response()->json(['success' => false,
                                     'message' => 'Could not found selected package',
                                    ], $this->successStatus);*/

                return response()->json(['success'=>false,'errors' =>['exception' => ['Could not found selected package']]], $this->successStatus);
            }
            
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }

    /** 
     * Get Package api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getCounsellorPackages(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'listing_id' => 'required',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }
            //$user = Auth::user()->id;
            $userListing = Listing::where('id', $request->listing_id)->where('status', '1')->first();
            if(!empty($userListing))
            {
                $allPackages = Package::where('user_id', $userListing->user_id)->paginate(8); 
                if(count($allPackages) > 0)
                {
                    return response()->json(['success' => true,
                                         'packages' => $allPackages,
                                        ], $this->successStatus); 
                }
                else
                {
                    return response()->json(['success'=>false,'errors' =>['exception' => ['No package found for this counsellor']]], $this->successStatus);

                }
            }
            else
            {
                return response()->json(['success'=>false,'errors' =>['exception' => ['Listing id is not valid']]], $this->successStatus);
            }
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }


    /** 
     * Edit Package api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function editPackage(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'package_id' => 'required', 
                // 'package_name' => 'required',  
                // 'package_description' => 'required', 
                // 'session_minutes' => 'required', 
                // 'session_hours' => 'required', 
                // 'amount' => 'required',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }

            
            $package = Package::where('id', $request->package_id)->first();
            
            if(isset($request->package_name) && !empty($request->package_name))
            {
                $exits = Package::where('id','!=', $request->package_id)->where('user_id', Auth::user()->id)->where('package_name', $request->package_name)->count();
                if ($exits > 0)
                {
                    return response()->json(['success'=>false,'errors' =>['exception' => ['Package name already exist']]], $this->successStatus); 
                }
                $package->package_name = $request->package_name; 
            }
            if(isset($request->package_description) && !empty($request->package_description))
            {
                $package->package_description = $request->package_description; 
            }
            if(isset($request->session_minutes) && !empty($request->session_minutes))
            {
                $package->session_minutes = $request->session_minutes; 
            }
            if(isset($request->session_hours))
            {
                $package->session_hours = $request->session_hours; 
            }
            if(isset($request->amount) && !empty($request->amount))
            {
                $package->amount = $request->amount; 
            }
            if(isset($request->no_of_slots) && !empty($request->no_of_slots))
            {
                $package->no_of_slots = $request->no_of_slots; 
            }
            $package->save();

            return response()->json(['success' => true,
                                     'message' => 'Package updated',
                                     'package' => $package,
                                    ], $this->successStatus); 

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }


    /** 
     * Delete Package api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function deletePackage(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                //'user_id' => 'required', 
                'package_id' => 'required', 
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }

            $user = Auth::user()->id;

            $booking = Booking::where('package_id', $request->package_id)->count();
            if($booking > 0)
            {
                /*return response()->json(['success' => false,
                                     'message' => 'Can not delete selected package as it is currently in use',
                                    ], $this->successStatus);*/

                return response()->json(['success'=>false,'errors' =>['exception' => ['Can not delete selected package as it is currently in use']]], $this->successStatus);
            }
            else
            {
                $package = Package::where(['id'=> $request->package_id, 'user_id'=> $user])->delete();

                if($package)
                {
                    $profilePercentage = $this->profileStatus(Auth::user()->id);
                    $userData = User::where('id', Auth::user()->id)->first();
                    return response()->json(['success' => true,
                                         'message' => 'Package deleted successfully',
                                         'user' =>  $userData
                                        ], $this->successStatus);
                }
                else
                {
                    /*return response()->json(['success' => false,
                                         'message' => 'This package does not exist',
                                        ], $this->successStatus);*/

                    return response()->json(['success'=>false,'errors' =>['exception' => ['This package does not exist']]], $this->successStatus);
                }
            }
            

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }


    /** 
     * Get Package with breaks 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getPackagesWithBreaks(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'package_id' => 'required',
                'user_id' => 'required',
                'counsellor_id' =>  'required',
                //'date' => 'required|date_format:Y-m-d',
                'date' => 'required|date_format:Y-m-d|after_or_equal:today',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }
            //$user = Auth::user()->id;
            $date = $request->date;
            $selectedDate = Carbon::parse($date)->format('l');
            $day = strtolower($selectedDate);

            $user = User::where('id', $request->user_id)->first();
            $counsellor = User::where('id', $request->counsellor_id)->first();
            
            $getAvailability = Availability::where('user_id', $request->counsellor_id)->where('availaible_days', $day)->first(); 

            $package = Package::where('id', $request->package_id)->where('user_id', $request->counsellor_id)->first();

            $arr = [];
            if(!empty($package) && !empty($getAvailability))
            {
                $sessionMin = $package->session_minutes;
                $sessionHours = $package->session_hours;
                if($sessionHours != 0)
                {
                    $sessionTime = $sessionHours * 60;
                    $sessionTime = $sessionTime + $sessionMin;
                }
                else
                {
                    $sessionTime = $sessionMin;
                }
                //$sessionTime = 23;
                $bkdSlot = Booking::where('booking_date', $date)->where('counsellor_id', $request->counsellor_id)->where('status', '1')->get();
                if(!empty($bkdSlot))
                {
                    $bookedSlot = $bkdSlot->pluck('slot')->toArray();
                    $myAvailableHours = AvailaibleHours::where('availability_id', $getAvailability->id)
                    //->whereNotIn('from_time', $bookedSlot)
                    ->get();
                }
                else
                {
                    $myAvailableHours = AvailaibleHours::where('availability_id', $getAvailability->id)->get();
                }
                

                foreach ($myAvailableHours as $hours) 
                {
                    if($hours->from_time == '00:00 AM')
                    {
                        $hours->from_time = '12:00 AM';
                    }

                    if($hours->to_time == '00:00 AM')
                    {
                        $hours->to_time = '12:00 AM';
                    }

                    $fromTime = date("H:i", strtotime($hours->from_time));
                    $toTime = date("H:i", strtotime($hours->to_time));

                    $data = $this->SplitTime($fromTime, $toTime, $sessionTime, $date);

                    $offsetUser = Carbon::now($user->timezone)->offsetMinutes;
                    $offsetCounsellor = Carbon::now($counsellor->timezone)->offsetMinutes;
                         
                /****from - to time conversion*/
                        
                    $timestampFrom = $date.' '.$hours->from_time;
                    $timestampTo = $date.' '.$hours->to_time;
                     
                    
                    $date_From = Carbon::createFromFormat('Y-m-d g:i A', $timestampFrom, $counsellor->timezone);
                    $utcFrom = $date_From->setTimezone('UTC');

                    $date_To = Carbon::createFromFormat('Y-m-d g:i A', $timestampTo, $counsellor->timezone);
                    $utcTo = $date_To->setTimezone('UTC');
                    

                        $utimesFrom = $utcFrom->format('Y-m-d g:i A');
                        $utimesFrom = Carbon::parse($utimesFrom)->addMinutes($offsetUser)->format('g:i A');

                        $utimesToTimeStamp = $utcTo->format('Y-m-d g:i A');
                        $utimesToDate = Carbon::parse($utimesToTimeStamp)->addMinutes($offsetUser)->format('Y-m-d');
                        $utimesTo = Carbon::parse($utimesToTimeStamp)->addMinutes($offsetUser)->format('g:i A');
                        /*if($date == $utimesToDate)
                        {
                            $utimesTo = $utimesTo;
                        }
                        else
                        {
                            while($utimesToDate > $date) {
                              $utimesTo = Carbon::parse($utimesToTimeStamp)->subMinutes($sessionTime)->format('g:i A');;
                            } 
                        }*/

                /********************/
                        
                        

                        $existingSlotArray = [];
                        if($user->timezone == $counsellor->timezone)
                        {
                            $bookingData = Booking::where('booking_date', $date)->where('counsellor_id', $request->counsellor_id)->where('status', '1')->get();

                            $allBookingData = Booking::where('booking_date', $date)->where('user_id', $request->user_id)->where('status', '1')->get();

                            $books = [];
                            if(count($bookingData) > 0) {
                                foreach ($bookingData as $bKey => $row) {
                                    $t = date('h:i A',strtotime( $date . ' '.$row->slot ));
                                    $books[] = $t; 
                                }
                            }

                            if(count($allBookingData) > 0) {
                                foreach ($allBookingData as $bookedPackageData) {
                                    $t = date('h:i A',strtotime( $date . ' '.$bookedPackageData->slot ));
                                    $books[] = $t; 
                                }
                            }

$existingBookedSlotArray = [];
                            foreach($data as $key => $datas)
                            {
                                if(count($bookingData) > 0) {
                                    foreach ($bookingData as $bkKey => $row) {

                                        $inputSlot = $date.' '.$datas;
                                        $inputSlotTimestamp = Carbon::createFromFormat('Y-m-d h:i A', $inputSlot);
                                        $inputformatedTime = $inputSlotTimestamp->format('Y-m-d h:i A');
                                        $extendedSlot = Carbon::parse($inputformatedTime)->addMinutes($sessionTime)->format('h:i A');
                                        

                                        $dateAndTime = $date.' '.$row->slot;
                                        $bookedPackage = Package::where('id', $row->package_id)->first();

                                        $bookedSessionMin = $bookedPackage->session_minutes;
                                        $sessionHours = $bookedPackage->session_hours;
                                        if($sessionHours != 0)
                                        {
                                            $bookedSessionTime = $sessionHours * 60;
                                            $bookedSessionTime = $bookedSessionTime + $bookedSessionMin;
                                        }
                                        else
                                        {
                                            $bookedSessionTime = $bookedSessionMin;
                                        }
                                        
                                        $inputTimestamp = Carbon::createFromFormat('Y-m-d h:i A', $dateAndTime);
                                        $formatedTime = $inputTimestamp->format('Y-m-d h:i A');
                                        $extendedBookedSlot = Carbon::parse($formatedTime)->addMinutes($bookedSessionTime)->format('h:i A');
                                        


$date1 = DateTime::createFromFormat('h:i A', $row->slot);
$date2 = DateTime::createFromFormat('h:i A', $datas);
$date3 = DateTime::createFromFormat('h:i A', $extendedSlot);

$date4 = DateTime::createFromFormat('h:i A', $datas);
$date5 = DateTime::createFromFormat('h:i A', $row->slot);
$date6 = DateTime::createFromFormat('h:i A', $extendedBookedSlot);

                                        
//return $extendedsSlot;
                                        if (($date1 > $date2 && $date1 < $date3) || ($date4 > $date5 && $date4 < $date6) || (in_array($datas, $books)))
                                        {
                                            $existingBookedSlotArray[] = $datas;
                                            $existingSlotArray[] = $datas;
                                            //break;
                                        }
                                        else
                                        {
                                            $existingSlotArray[] = $datas;
                                            //break;
                                        }
                                        
                                    }
                                    
                                    //$existingSlotArray = array_diff($existingSlotArray,$existingBookedSlotArray);
                                }
                                else
                                {
                                    if( !in_array($datas, $books) )
                                    {
                                        $existingSlotArray[] = $datas;
                                    }
                                }

                            }
//return $existingSlotArray;
                            
                            //$d = array_intersect($existingSlotArray,$books); 
                            //$result = array_diff($existingSlotArray, $d);  
                            $result = array_unique($existingSlotArray);

                            foreach($result as $datas)
                            {
                                //$arr[$hours->from_time.' - '.$hours->to_time][] = (in_array($datas, $existingBookedSlotArray)) ? array($datas=>1) : array($datas => 0);
                                $arr[$hours->from_time.' - '.$hours->to_time][] = (in_array($datas, $existingBookedSlotArray)) ? array("slot" => $datas,"enabled" => 0) : array("slot" => $datas,"enabled" => 1);
                            }
                        }
                        else
                        {
                            $existingBookedSlotArray = [];
                            $existingSlotArrayss = [];
                            $tempArr = [];
                            $bookingData = Booking::where('booking_date', $date)->where('counsellor_id', $request->counsellor_id)->where('status', '1')->get();

                            $allBookingData = Booking::where('booking_date', $date)->where('user_id', $request->user_id)->where('status', '1')->get();

                            $books = [];
                            if(count($bookingData) > 0) {
                                foreach ($bookingData as $key => $row) {
                                    $t = date('h:i A',strtotime( $date . ' '.$row->slot ));
                                    $books[] = $t; //$row->counsellor_timezone_slot;
                                }
                            }

                            if(count($allBookingData) > 0) {
                                foreach ($allBookingData as $bookedPackageData) {
                                    $t = date('h:i A',strtotime( $date . ' '.$bookedPackageData->slot ));
                                    $books[] = $t; //$row->counsellor_timezone_slot;
                                }
                            }

                            
                            $currentDate = Carbon::now($user->timezone)->format('Y-m-d');
                            if(count($data) > 0) {
                                foreach($data as $key => $datas)
                                {
                                    if(count($bookingData) > 0)
                                    {
                                        foreach ($bookingData as $bkKey => $row)
                                        {





                                            $inputSlot = $date.' '.$datas;
                                            $inputSlotTimestamp = Carbon::createFromFormat('Y-m-d h:i A', $inputSlot);
                                            $inputformatedTime = $inputSlotTimestamp->format('Y-m-d h:i A');
                                            $extendedSlot = Carbon::parse($inputformatedTime)->addMinutes($sessionTime)->format('h:i A');
                                            

                                            $dateAndTime = $date.' '.$row->slot;
                                            $bookedPackage = Package::where('id', $row->package_id)->first();

                                            $bookedSessionMin = $bookedPackage->session_minutes;
                                            $sessionHours = $bookedPackage->session_hours;
                                            if($sessionHours != 0)
                                            {
                                                $bookedSessionTime = $sessionHours * 60;
                                                $bookedSessionTime = $bookedSessionTime + $bookedSessionMin;
                                            }
                                            else
                                            {
                                                $bookedSessionTime = $bookedSessionMin;
                                            }
                                            
                                            $inputTimestamp = Carbon::createFromFormat('Y-m-d h:i A', $dateAndTime);
                                            $formatedTime = $inputTimestamp->format('Y-m-d h:i A');
                                            $extendedBookedSlot = Carbon::parse($formatedTime)->addMinutes($bookedSessionTime)->format('h:i A');
                                            


    $date1 = DateTime::createFromFormat('h:i A', $row->slot);
    $date2 = DateTime::createFromFormat('h:i A', $datas);
    $date3 = DateTime::createFromFormat('h:i A', $extendedSlot);

    $date4 = DateTime::createFromFormat('h:i A', $datas);
    $date5 = DateTime::createFromFormat('h:i A', $row->slot);
    $date6 = DateTime::createFromFormat('h:i A', $extendedBookedSlot);

                                            
    //return $extendedBookedSlot;
                                            if (($date1 > $date2 && $date1 < $date3) || ($date4 > $date5 && $date4 < $date6) || (in_array($datas, $books)))
                                            {
                                                $existingBookedSlotArray[] = $datas;
                                            }
                                              
                                        }// bookedslot foreach
                                       
                                       
                                        $currentTimestamp = Carbon::now($user->timezone);
                                   
                                        $dateAndTime = $date.' '.$datas;

                                        $inputTimestamp = Carbon::createFromFormat('Y-m-d h:i A', $dateAndTime, $counsellor->timezone);
                                        $myutc = $inputTimestamp->setTimezone('UTC');
                                        
                                        
                                        $userTime = $myutc->format('Y-m-d h:i A');
                                        $userTimeSlot = Carbon::parse($userTime)->addMinutes($offsetUser)->format('h:i A');
                                        $uDate = Carbon::parse($userTime)->addMinutes($offsetUser)->format('d');

                                        
                                        $idate = Carbon::parse($dateAndTime)->format('d');
                                     
                                        if( ($inputTimestamp > $currentTimestamp) && ($idate == $uDate)) 
                                        {
                                            $existingSlotArray[] = $userTimeSlot;
                                        }
                                        if( (in_array($datas, $books) && in_array($datas, $existingBookedSlotArray) )) 
                                        {
                                            $existingSlotArrayss[] = $userTimeSlot;
                                        }
                                      
                                        //$existingSlotArray = array_diff($existingSlotArray,$existingBookedSlotArray);
                                        
                                    }// count bookingdata
                                    else
                                    {
                                        $currentTimestamp = Carbon::now($user->timezone);
                                   
                                        $dateAndTime = $date.' '.$datas;

                                        $inputTimestamp = Carbon::createFromFormat('Y-m-d h:i A', $dateAndTime, $counsellor->timezone);
                                        $myutc = $inputTimestamp->setTimezone('UTC');
                                        
                                        
                                        $userTime = $myutc->format('Y-m-d h:i A');
                                        $userTimeSlot = Carbon::parse($userTime)->addMinutes($offsetUser)->format('h:i A');
                                        $uDate = Carbon::parse($userTime)->addMinutes($offsetUser)->format('d');

                                        
                                        $idate = Carbon::parse($dateAndTime)->format('d');
                                  
                                        if( !in_array($datas, $books) && ($inputTimestamp > $currentTimestamp) && ($idate == $uDate)) //($datas >= $bookingSlot->slot) && ($datas <= $fdate))
                                        {
                                            $existingSlotArray[] = $userTimeSlot;
                                        }
                                    }
                                            
                                }
                            }
                       
                            
                            //$d = array_intersect($existingSlotArray,$books); 
                            //$result = array_diff($existingSlotArray, $d);
                            $result = array_unique($existingSlotArray);
 
                            foreach($result as $key => $datas)
                            {
                                $arr[$utimesFrom.' - '.$utimesTo][] = (in_array($datas, $existingSlotArrayss)) ? array("slot" => $datas,"enabled" => 0) : array("slot" => $datas,"enabled" => 1);
                            }
                        }

                        

                }
                return response()->json(['success' => true,
                                     'data' => $arr,
                                     'temp'=>$existingBookedSlotArray
                                    ], $this->successStatus);
                
            }
            else
            {
                 return response()->json(['success' => true,
                                     'data' => $arr,
                                    ], $this->successStatus);
            }
            
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }

    /*public function getPackagesWithBreaks(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'package_id' => 'required',
                'user_id' => 'required',
                'counsellor_id' =>  'required',
                //'date' => 'required|date_format:Y-m-d',
                'date' => 'required|date_format:Y-m-d|after_or_equal:today',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }
            //$user = Auth::user()->id;
            $date = $request->date;
            $selectedDate = Carbon::parse($date)->format('l');
            $day = strtolower($selectedDate);

            $user = User::where('id', $request->user_id)->first();
            $counsellor = User::where('id', $request->counsellor_id)->first();
            
            $getAvailability = Availability::where('user_id', $request->counsellor_id)->where('availaible_days', $day)->first(); 

            $package = Package::where('id', $request->package_id)->where('user_id', $request->counsellor_id)->first();

            $arr = [];
            if(!empty($package) && !empty($getAvailability))
            {
                $sessionMin = $package->session_minutes;
                $sessionHours = $package->session_hours;
                if($sessionHours != 0)
                {
                    $sessionTime = $sessionHours * 60;
                    $sessionTime = $sessionTime + $sessionMin;
                }
                else
                {
                    $sessionTime = $sessionMin;
                }
                //$sessionTime = 23;
                $bkdSlot = Booking::where('booking_date', $date)->where('counsellor_id', $request->counsellor_id)->get();
                if(!empty($bkdSlot))
                {
                    $bookedSlot = $bkdSlot->pluck('slot')->toArray();
                    $myAvailableHours = AvailaibleHours::where('availability_id', $getAvailability->id)
                    //->whereNotIn('from_time', $bookedSlot)
                    ->get();
                }
                else
                {
                    $myAvailableHours = AvailaibleHours::where('availability_id', $getAvailability->id)->get();
                }
                

                foreach ($myAvailableHours as $hours) 
                {
                    if($hours->from_time == '00:00 AM')
                    {
                        $hours->from_time = '12:00 AM';
                    }

                    if($hours->to_time == '00:00 AM')
                    {
                        $hours->to_time = '12:00 AM';
                    }

                    $fromTime = date("H:i", strtotime($hours->from_time));
                    $toTime = date("H:i", strtotime($hours->to_time));

                    $data = $this->SplitTime($fromTime, $toTime, $sessionTime, $date);

                    $offsetUser = Carbon::now($user->timezone)->offsetMinutes;
                    $offsetCounsellor = Carbon::now($counsellor->timezone)->offsetMinutes;
                         
              
                        
                    $timestampFrom = $date.' '.$hours->from_time;
                    $timestampTo = $date.' '.$hours->to_time;
                     
                    
                    $date_From = Carbon::createFromFormat('Y-m-d g:i A', $timestampFrom, $counsellor->timezone);
                    $utcFrom = $date_From->setTimezone('UTC');

                    $date_To = Carbon::createFromFormat('Y-m-d g:i A', $timestampTo, $counsellor->timezone);
                    $utcTo = $date_To->setTimezone('UTC');
                    

                        $utimesFrom = $utcFrom->format('Y-m-d g:i A');
                        $utimesFrom = Carbon::parse($utimesFrom)->addMinutes($offsetUser)->format('g:i A');

                        $utimesTo = $utcTo->format('Y-m-d g:i A');
                        $utimesTo = Carbon::parse($utimesTo)->addMinutes($offsetUser)->format('g:i A');


                        
                        

                        $existingSlotArray = [];
                        if($user->timezone == $counsellor->timezone)
                        {
                            $bookingData = Booking::where('booking_date', $date)->where('counsellor_id', $request->counsellor_id)->get();
                            $books = [];
                            if(count($bookingData) > 0) {
                                foreach ($bookingData as $bKey => $row) {
                                    $t = date('h:i A',strtotime( $date . ' '.$row->slot ));
                                    $books[] = $t; 
                                }
                            }
$existingBookedSlotArray = [];
                            foreach($data as $key => $datas)
                            {
                                if(count($bookingData) > 0) {
                                    foreach ($bookingData as $bkKey => $row) {

                                        $inputSlot = $date.' '.$datas;
                                        $inputSlotTimestamp = Carbon::createFromFormat('Y-m-d h:i A', $inputSlot);
                                        $inputformatedTime = $inputSlotTimestamp->format('Y-m-d h:i A');
                                        $extendedSlot = Carbon::parse($inputformatedTime)->addMinutes($sessionTime)->format('h:i A');
                                        

                                        $dateAndTime = $date.' '.$row->slot;
                                        $bookedPackage = Package::where('id', $row->package_id)->first();

                                        $bookedSessionMin = $bookedPackage->session_minutes;
                                        $sessionHours = $bookedPackage->session_hours;
                                        if($sessionHours != 0)
                                        {
                                            $bookedSessionTime = $sessionHours * 60;
                                            $bookedSessionTime = $bookedSessionTime + $bookedSessionMin;
                                        }
                                        else
                                        {
                                            $bookedSessionTime = $bookedSessionMin;
                                        }
                                        
                                        $inputTimestamp = Carbon::createFromFormat('Y-m-d h:i A', $dateAndTime);
                                        $formatedTime = $inputTimestamp->format('Y-m-d h:i A');
                                        $extendedBookedSlot = Carbon::parse($formatedTime)->addMinutes($bookedSessionTime)->format('h:i A');
                                        


$date1 = DateTime::createFromFormat('h:i A', $row->slot);
$date2 = DateTime::createFromFormat('h:i A', $datas);
$date3 = DateTime::createFromFormat('h:i A', $extendedSlot);

$date4 = DateTime::createFromFormat('h:i A', $datas);
$date5 = DateTime::createFromFormat('h:i A', $row->slot);
$date6 = DateTime::createFromFormat('h:i A', $extendedBookedSlot);

                                        
//return $extendedsSlot;
                                        if (($date1 > $date2 && $date1 < $date3) || ($date4 > $date5 && $date4 < $date6))
                                        {
                                            $existingBookedSlotArray[] = $datas;
                                            //break;
                                        }
                                        else
                                        {
                                            $existingSlotArray[] = $datas;
                                            //break;
                                        }
                                        
                                    }
                                    
                                    $existingSlotArray = array_diff($existingSlotArray,$existingBookedSlotArray);
                                }
                                else
                                {
                                    if( !in_array($datas, $books) )
                                    {
                                        $existingSlotArray[] = $datas;
                                    }
                                }

                            }
//return $existingSlotArray;
                            
                            $d = array_intersect($existingSlotArray,$books); 
                            $result = array_diff($existingSlotArray, $d);  
                            $result = array_unique($result);

                            foreach($result as $datas)
                            {
                                $arr[$hours->from_time.' - '.$hours->to_time][] = $datas;
                            }
                        }
                        else
                        {
                            $existingBookedSlotArray = [];
                            $tempArr = [];
                            $bookingData = Booking::where('booking_date', $date)->where('counsellor_id', $request->counsellor_id)->get();
                            $books = [];
                            if(count($bookingData) > 0) {
                                foreach ($bookingData as $key => $row) {
                                    $t = date('h:i A',strtotime( $date . ' '.$row->slot ));
                                    $books[] = $t; //$row->counsellor_timezone_slot;
                                }
                            }

                            
                            $currentDate = Carbon::now($user->timezone)->format('Y-m-d');
                            if(count($data) > 0) {
                                foreach($data as $key => $datas)
                                {
                                    if(count($bookingData) > 0)
                                    {
                                        foreach ($bookingData as $bkKey => $row)
                                        {





                                            $inputSlot = $date.' '.$datas;
                                            $inputSlotTimestamp = Carbon::createFromFormat('Y-m-d h:i A', $inputSlot);
                                            $inputformatedTime = $inputSlotTimestamp->format('Y-m-d h:i A');
                                            $extendedSlot = Carbon::parse($inputformatedTime)->addMinutes($sessionTime)->format('h:i A');
                                            

                                            $dateAndTime = $date.' '.$row->slot;
                                            $bookedPackage = Package::where('id', $row->package_id)->first();

                                            $bookedSessionMin = $bookedPackage->session_minutes;
                                            $sessionHours = $bookedPackage->session_hours;
                                            if($sessionHours != 0)
                                            {
                                                $bookedSessionTime = $sessionHours * 60;
                                                $bookedSessionTime = $bookedSessionTime + $bookedSessionMin;
                                            }
                                            else
                                            {
                                                $bookedSessionTime = $bookedSessionMin;
                                            }
                                            
                                            $inputTimestamp = Carbon::createFromFormat('Y-m-d h:i A', $dateAndTime);
                                            $formatedTime = $inputTimestamp->format('Y-m-d h:i A');
                                            $extendedBookedSlot = Carbon::parse($formatedTime)->addMinutes($bookedSessionTime)->format('h:i A');
                                            


    $date1 = DateTime::createFromFormat('h:i A', $row->slot);
    $date2 = DateTime::createFromFormat('h:i A', $datas);
    $date3 = DateTime::createFromFormat('h:i A', $extendedSlot);

    $date4 = DateTime::createFromFormat('h:i A', $datas);
    $date5 = DateTime::createFromFormat('h:i A', $row->slot);
    $date6 = DateTime::createFromFormat('h:i A', $extendedBookedSlot);

                                            
    //return $extendedBookedSlot;
                                            if (($date1 > $date2 && $date1 < $date3) || ($date4 > $date5 && $date4 < $date6))
                                            {
                                                $existingBookedSlotArray[] = $datas;
                                            }
                                              
                                        }// bookedslot foreach
                                       
                                       
                                       	$currentTimestamp = Carbon::now($user->timezone);
                                   
		                                $dateAndTime = $date.' '.$datas;

		                                $inputTimestamp = Carbon::createFromFormat('Y-m-d h:i A', $dateAndTime, $counsellor->timezone);
		                                $myutc = $inputTimestamp->setTimezone('UTC');
		                                
		                                
		                                $userTime = $myutc->format('Y-m-d h:i A');
		                                $userTimeSlot = Carbon::parse($userTime)->addMinutes($offsetUser)->format('h:i A');
		                                $uDate = Carbon::parse($userTime)->addMinutes($offsetUser)->format('d');

		                                
		                                $idate = Carbon::parse($dateAndTime)->format('d');
		                         	 
		                                if( (!in_array($datas, $books) && !in_array($datas, $existingBookedSlotArray) && $inputTimestamp > $currentTimestamp) && ($idate == $uDate)) 
		                                {
		                                    $existingSlotArray[] = $userTimeSlot;
		                                }
                                      
                                        //$existingSlotArray = array_diff($existingSlotArray,$existingBookedSlotArray);
                                        
                                    }// count bookingdata
                                    else
                                    {
                                        $currentTimestamp = Carbon::now($user->timezone);
                                   
                                        $dateAndTime = $date.' '.$datas;

                                        $inputTimestamp = Carbon::createFromFormat('Y-m-d h:i A', $dateAndTime, $counsellor->timezone);
                                        $myutc = $inputTimestamp->setTimezone('UTC');
                                        
                                        
                                        $userTime = $myutc->format('Y-m-d h:i A');
                                        $userTimeSlot = Carbon::parse($userTime)->addMinutes($offsetUser)->format('h:i A');
                                        $uDate = Carbon::parse($userTime)->addMinutes($offsetUser)->format('d');

                                        
                                        $idate = Carbon::parse($dateAndTime)->format('d');
                                  
                                        if( !in_array($datas, $books) && ($inputTimestamp > $currentTimestamp) && ($idate == $uDate)) //($datas >= $bookingSlot->slot) && ($datas <= $fdate))
                                        {
                                            $existingSlotArray[] = $userTimeSlot;
                                        }
                                    }
                                            
                                }
                            }
                       
                            
                            $d = array_intersect($existingSlotArray,$books); 
                            $result = array_diff($existingSlotArray, $d);
                            $result = array_unique($result);
 
                            foreach($result as $key => $datas)
                            {
                                $arr[$utimesFrom.' - '.$utimesTo][] = $datas;
                            }
                        }

                        

                }
                return response()->json(['success' => true,
                                     'data' => $arr,
                                     'temp'=>$existingBookedSlotArray
                                    ], $this->successStatus);
                
            }
            else
            {
                 return response()->json(['success' => true,
                                     'data' => $arr,
                                    ], $this->successStatus);
            }
            
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }*/

    public function SplitTime($StartTime, $EndTime, $Duration, $date){
    $ReturnArray = [];
    $StartTime    = strtotime ($StartTime); 
    $EndTime      = strtotime ($EndTime); 

    $AddMins  = $Duration * 60;
    $i = 0;
    while ((($StartTime) <= ($EndTime-$AddMins))) 
    {
        $ReturnArray[$i] = date ("h:i A", $StartTime);
        
        $fromTime = date("h:i A", strtotime($ReturnArray[$i]));
        $fromTime = strtotime(($fromTime));

            $StartTime += $AddMins; 
            $i++;
        
    }
    return $ReturnArray;
    }
     
     
}
