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
			

			$days = $request->availaible_days;
			$reqJSON = json_decode($days, true);

			// echo "<pre>";
			// print_r($reqJSON);
			// exit;


			foreach ($reqJSON as $key => $reqJSONs) 
			{
				/*switch ($key) 
				{
				  	case "sunday":

					$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = $key;
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			    	foreach ($reqJSONs as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    	break;

			    	case "monday":

					$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = $key;
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			    	foreach ($reqJSONs as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    	break;

			    	case "tuesday":

					$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = $key;
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			    	foreach ($reqJSONs as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    	break;

			    	case "wednesday":

					$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = $key;
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			    	foreach ($reqJSONs as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    	break;

			    	case "thursday":

					$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = $key;
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			    	foreach ($reqJSONs as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    	break;

			    	case "friday":

					$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = $key;
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			    	foreach ($reqJSONs as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    	break;

			    	case "saturday":

					$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = $key;
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			    	foreach ($reqJSONs as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    	break;

			  	default:
			    echo "";
				}*/

				
			}	

			

























			/*switch ($requestJSON) 
			{
			  case "sunday":

			    	$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = 'sunday';
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			  		foreach ($days[0] as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    break;
			  case "monday":
			    	
			  		$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = 'monday';
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			  		foreach ($days[1] as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    break;
			  case "tuesday":
			    
			  		$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = 'tuesday';
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			  		foreach ($days[2] as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    break;
			  case "wednesday":
			    	
			  		$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = 'wednesday';
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			  		foreach ($days[3] as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    break;
			  case "thursday":
			    	
			  		$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = 'thursday';
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			  		foreach ($days[4] as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    break;
			  case "friday":
			   		
			  		$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = 'friday';
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			  		foreach ($days[5] as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    break;
			  case "saturday":
			    	
			  		$availDay = new Availability;
			    	$availDay->user_id = $user;
			    	$availDay->availaible_days = 'saturday';
			    	$availDay->breaks = $request->breaks;
			    	$availDay->save();

			  		foreach ($days[6] as $hours) 
			  		{
			  			$availHour = new AvailaibleHours;
			  			$availHour->availability_id = $availDay->id;
			  			$availHour->from_time = $hours->open;
			  			$availHour->to_time = $hours->close;
			  			$availHour->save();
			  		}

			    break;

			  default:
			    echo "";
			}*/










	        return response()->json(['success' => true,
	            					 'message' => 'Timings Added!',
	            					], $this->successStatus); 

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }
}