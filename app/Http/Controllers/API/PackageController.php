<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\AvailaibleHours;
use App\Booking;
use App\Availability;
use Event;
use Carbon\Carbon;
use App\Events\UserRegisterEvent;

class PackageController extends Controller
{
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

    	        return response()->json(['success' => true,
    	            					 'package' => $package,
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
            //$allPackages = Package::where('user_id', $user)->paginate(8); 
            $allPackages = Package::where('user_id', $user)->get(); 

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
            $allPackages = Package::where('user_id', $request->user_id)->get(); 

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
                    return response()->json(['success' => true,
                                         'message' => 'Package deleted successfully',
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
                'date' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }
            //$user = Auth::user()->id;
            $date = $request->date;
            $selectedDate = Carbon::parse($date)->format('l');
            $day = strtolower($selectedDate);
            
            $getAvailability = Availability::where('user_id', $request->user_id)->where('availaible_days', $day)->first(); 

            $package = Package::where('id', $request->package_id)->where('user_id', $request->user_id)->first();

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
                $myAvailableHours = AvailaibleHours::where('availability_id', $getAvailability->id)->get();

                foreach ($myAvailableHours as $hours) 
                {
                    
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

                        foreach($data as $key => $datas)
                        {
                            $currentTime = Carbon::now()->toTimeString();
                            $his = date("h:i:s", strtotime($datas));

                            $bookingSlot = Booking::where('booking_date', $date)->first();
                            //return $bookingSlot;
                            if(!empty($bookingSlot))
                            {
                                if($his > $currentTime)
                                $arr[$hours->from_time.' - '.$hours->to_time][] = ($bookingSlot->slot == $datas) ? ("") : ($datas);
                            }
                            else
                            {
                                $arr[$hours->from_time.' - '.$hours->to_time][] = $datas;
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
