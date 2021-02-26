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
use App\Availability;
use Event;
use Carbon\Carbon;
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
                'user_id' => 'required',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }
            //$user = Auth::user()->id;
            $allPackages = Package::where('user_id', $request->user_id)->paginate(8); 

            if(count($allPackages) > 0)
            {
                return response()->json(['success' => true,
                                     'packages' => $allPackages,
                                    ], $this->successStatus); 
            }
            else
            {
                /*return response()->json(['success' => false,
                                     'message' => 'No package found for this counsellor',
                                    ], $this->successStatus); */

                return response()->json(['success'=>false,'errors' =>['exception' => ['No package found for this counsellor']]], $this->successStatus);

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

                    /*echo  'from: '.$fromTime.'</br>';
                    echo  'to: '.$toTime.'</br>';*/

                    $data = $this->SplitTime($fromTime, $toTime, $sessionTime, $date);

                    //return $data;

                    /*$data = [];
                    $fromTime    = strtotime ($fromTime); 
                    $toTime      = strtotime ($toTime); 

                    $AddMins  = $sessionTime * 60;
                    $i = 0;*/


                    /*while ((($fromTime) < ($toTime))) 
                    {
                        $data[$i] = date ("G:i A", $fromTime);
                        
                        $fromTime = date("H:i A", strtotime($data[$i]));
                        $fromTime = strtotime(($fromTime));

                            $fromTime += $AddMins; 
                            $i++;
                        
                    }*/
                        $offsetUser = Carbon::now($user->timezone)->offsetMinutes;
                        $offsetUser = $offsetUser/2;

                        $slotFromTimeUser = Carbon::parse($hours->from_time);
                        $convertedFromTimeUser = $slotFromTimeUser->addMinutes($offsetUser)->format('g:i A');

                        $slotToTimeUser = Carbon::parse($hours->to_time);
                        $convertedToTimeUser = $slotToTimeUser->addMinutes($offsetUser)->format('g:i A');
                    
                        $existingSlotArray = [];
                        if($user->timezone == $counsellor->timezone)
                        {
                            foreach($data as $key => $datas)
                            {
                                
                                $bookingData = Booking::where('booking_date', $date)->where('counsellor_id', $request->counsellor_id)->get();
                   
                                foreach($bookingData as $bookingSlot)
                                {
                                    $timeInTwentyFourHour = date("H:i", strtotime($bookingSlot->slot));
                                    $parsedTime = Carbon::parse($timeInTwentyFourHour);
                                    $sessionMins = $parsedTime->addMinutes($sessionTime);

                                    $fdate = date('h:i A', strtotime($sessionMins));

                                    if(($datas >= $bookingSlot->slot) && ($datas <= $fdate))
                                    {
                                        $existingSlotArray[] = $datas;
                                    }
                                    
                                }
                                
                            }

                            $result = array_diff($data,$existingSlotArray); 

                            foreach($result as $key => $datas)
                            {
                                $arr[$hours->from_time.' - '.$hours->to_time][] = $datas;
                            }
                        }
                        else
                        {
                            foreach($data as $key => $datas)
                            {
                                
                                $bookingData = Booking::where('counsellor_booking_date', $date)->where('counsellor_id', $request->counsellor_id)->get();
                   
                                foreach($bookingData as $bookingSlot)
                                {
                                    $timeInTwentyFourHour = date("H:i", strtotime($bookingSlot->counsellor_timezone_slot));
                                    $parsedTime = Carbon::parse($timeInTwentyFourHour);
                                    $sessionMins = $parsedTime->addMinutes($sessionTime);

                                    $fdate = date('h:i A', strtotime($sessionMins));

                                    if(($datas >= $bookingSlot->counsellor_timezone_slot) && ($datas <= $fdate))
                                    {
                                        $existingSlotArray[] = $datas;
                                    }
                                    
                                }
                                
                            }

                            $result = array_diff($data,$existingSlotArray); 

                            foreach($result as $key => $datas)
                            {
                                $arr[$convertedFromTimeUser.' - '.$convertedToTimeUser][] = $datas;
                            }
                        }

                        

                }
                return response()->json(['success' => true,
                                     'data' => $arr,
                                    ], $this->successStatus);
                
            }
            else
            {
                 return response()->json(['success' => true,
                                     'data' => $arr,
                                    ], $this->successStatus);
            }
            


            /*if(!empty($getAvailability))
            {
                return response()->json(['success' => true,
                                     'data' => $arr,
                                    ], $this->successStatus); 
            }
            else
            {
                
            }*/
            

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }

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
