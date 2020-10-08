<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Availability; 
use App\AvailaibleHours;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Event;

class AvailabilityController extends Controller
{
    public $successStatus = 200;
	

    /** 
     * Create availability
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function addAvailability(Request $request) 
    {
    	try
        {
    		$validator = Validator::make($request->all(), [ 
	            'availaible_days' => 'required',  
	            'breaks' => 'required',
	        ]);

			if ($validator->fails()) 
            { 
	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
			}

			$user = Auth::user()->id;
			$input = $request->all(); 
			 

			$availableDays = explode(",", $request->availaible_days);
			foreach($availableDays as $availableDay)
			{
				//saving availability days
				$availability = new Availability;
				$availability->user_id = $user;
				$availability->availaible_days = $availableDay;
				$availability->breaks = $request->breaks;
				$availability->save();

				//saving available hours
				$availableFrom = explode(",", $request->from_time);

				foreach($availableFrom as $availableHour)
				{ 	
					//echo $availableHour;
					$availableSlots = explode("@", $availableHour);
					foreach($availableSlots as $availableSlot)
					{
						$hoursAvail = new AvailaibleHours;
						$hoursAvail->availability_id = $availability->id;
						$hoursAvail->from_time = $availableSlot;
						//$hoursAvail->to_time  = $request->to_time;
						$hoursAvail->save();



						/******/
						$availableTo = explode(",", $request->to_time);
						foreach($availableTo as $availableToTime)
						{
							$availableSlotsTo = explode("@", $availableToTime);
							foreach($availableSlotsTo as $availableSlotTo)
							{
								$toTimes = AvailaibleHours::where('id', $hoursAvail->id)->first();
								$toTimes->availability_id = $availability->id;
								//$toTimes->from_time = $availableSlot;
								$toTimes->to_time  = $availableSlotTo;
								$toTimes->save();
							}
						}


					}

				} 
				
			}






			//$input['user_id'] = $user;
	        //$package = Availability::create($input); 

	        return response()->json(['success' => true,
	            					 //'package' => $package,
	            					], $this->successStatus); 

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }
}
