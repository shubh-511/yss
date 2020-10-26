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
				switch ($key) 
				{
				  	case "sunday":
				  	if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			    	case "monday":

					if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			    	case "tuesday":

					if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			    	case "wednesday":

					if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			    	case "thursday":

					if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			    	case "friday":

					if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			    	case "saturday":

					if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			  	default:
			    echo "";
				}
			}	

	        return response()->json(['success' => true,
	            					 'message' => 'Timings Added!',
	            					], $this->successStatus); 

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }


    /** 
     * Get My Availability
     *  
     * @return \Illuminate\Http\Response 
     */ 
    public function getAvailability(Request $request) 
    {
    	try
        {
			$user = Auth::user()->id;
			$myAvailability = Availability::where('user_id', $user)->get();

			$common = [];
			$r = -1;
			foreach($myAvailability as $availability)
			{
				$r++;
				$myAvailableHours = AvailaibleHours::select('id','from_time as open','to_time as close')->where('availability_id', $availability->id)->get();
				
				//$common[$r]['id'] = $availability->id;
				//$common[$r]['user_id'] = $availability->user_id;
				$common[$r]['availaible_days'] = $availability->availaible_days;
				$common[$r]['hours'] = $myAvailableHours;

				
			}
			return ($common);

			
			if(count($myAvailability) > 0)
			{
				return response()->json(['success' => true,
	            					 'data' => '',
	            					], $this->successStatus);
			}
			else
			{
				return response()->json(['success' => false,
	            					 'message' => 'You have not added any timings!',
	            					], $this->successStatus);
			}
	         

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }


    /** 
     * Update availability
     *  
     * @return \Illuminate\Http\Response 
     */ 
    public function updateAvailability(Request $request) 
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
			$myAvailability = Availability::where('user_id', $user)->get();
			$myAvailableHours = [];
			foreach($myAvailability as $availability)
			{
				array_push($myAvailableHours, $availability->id);
			}

			Availability::where('user_id', $user)->delete();
			AvailaibleHours::whereIn('availability_id', $myAvailableHours)->delete();

			foreach ($reqJSON as $key => $reqJSONs) 
			{
				switch ($key) 
				{
				  	case "sunday":
				  	if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			    	case "monday":

					if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			    	case "tuesday":

					if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			    	case "wednesday":

					if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			    	case "thursday":

					if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			    	case "friday":

					if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			    	case "saturday":

					if(count($reqJSONs) > 0)
				  	{
						$availDay = new Availability;
				    	$availDay->user_id = $user;
				    	$availDay->availaible_days = $key;
				    	$availDay->breaks = $request->breaks;
				    	$availDay->save();

				    	foreach ($reqJSONs as $hours) 
				  		{
				  			$availHour = new AvailaibleHours;
				  			foreach($hours as $k => $hour)
				  			{
				  				
					  			$availHour->availability_id = $availDay->id;
					  			if($k == 'open')
					  			$availHour->from_time = $hour;
					  			elseif($k == 'close')
					  			$availHour->to_time = $hour;
					  			$availHour->save();
				  			}
				  		}
			  		}

			    	break;

			  	default:
			    echo "";
				}
			}	

	        return response()->json(['success' => true,
	            					 'message' => 'Timings updated!',
	            					], $this->successStatus); 

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
    }
        


}