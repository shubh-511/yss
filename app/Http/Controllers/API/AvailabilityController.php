<?php



namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Availability; 
use App\User; 
use App\AvailaibleHours;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Event;
use App\Traits\ProfileStatusTrait;

class AvailabilityController extends Controller
{
	use ProfileStatusTrait;
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
	            //'breaks' => 'required',
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
			$checkExisting = Availability::where('user_id', $user)->count();

			if($checkExisting == 0)
			{
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
				$profilePercentage = $this->profileStatus(Auth::user()->id);
				$userData = User::where('id', Auth::user()->id)->first();
				return response()->json(['success' => true,
	            					 'message' => 'Timings  Added!',
	            					 'user'	=>	$userData
	            					], $this->successStatus); 
			}
			else
			{
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
				$profilePercentage = $this->profileStatus(Auth::user()->id);
				$userData = User::where('id', Auth::user()->id)->first();
		        return response()->json(['success' => true,
		            					 'message' => 'Timings updated!',
		            					 'user'	=>	$userData
		            					], $this->successStatus);  
			}	

	        

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

			$avlbleDays = $myAvailability->pluck('availaible_days');
			$avlbleDays = $avlbleDays->toArray();


			if(count($myAvailability) > 0)
			{
				$common = [];
				$r = -1;
				
				foreach($myAvailability as $availability)
				{
					$myAvailableHours = AvailaibleHours::select('id','from_time as open','to_time as close')->where('availability_id', $availability->id)->get();
					
					if(in_array("sunday", $avlbleDays))
					{
						$common[$availability->availaible_days] = $myAvailableHours;
					}
					else
					{
						$common['sunday'] = [];
					}

					if(in_array("monday", $avlbleDays))
					{
						$common[$availability->availaible_days] = $myAvailableHours;
					}
					else
					{
						$common['monday'] = [];
					}

					if(in_array("tuesday", $avlbleDays))
					{
						$common[$availability->availaible_days] = $myAvailableHours;
					}
					else
					{
						$common['tuesday'] = [];
					}

					if(in_array("wednesday", $avlbleDays))
					{
						$common[$availability->availaible_days] = $myAvailableHours;
					}
					else
					{
						$common['wednesday'] = [];
					}

					if(in_array("thursday", $avlbleDays))
					{
						$common[$availability->availaible_days] = $myAvailableHours;
					}
					else
					{
						$common['thursday'] = [];
					}

					if(in_array("friday", $avlbleDays))
					{
						$common[$availability->availaible_days] = $myAvailableHours;
					}
					else
					{
						$common['friday'] = [];
					}

					if(in_array("saturday", $avlbleDays))
					{
						$common[$availability->availaible_days] = $myAvailableHours;
					}
					else
					{
						$common['saturday'] = [];
					}

					

					
				}
				return response()->json(['success' => true,
	            					 'data' => $common,
	            					], $this->successStatus);
			}
			else
			{
				/*return response()->json(['success' => false,
	            					 'message' => '',
	            					], $this->successStatus);*/
	            	$common = [];
	            	$myAvailableHours = [];

	            	$common['sunday'] = $myAvailableHours;
	            	$common['monday'] = $myAvailableHours;
	            	$common['tuesday'] = $myAvailableHours;
	            	$common['wednesday'] = $myAvailableHours;
	            	$common['thursday'] = $myAvailableHours;
	            	$common['friday'] = $myAvailableHours;
	            	$common['saturday'] = $myAvailableHours;

	            	return response()->json(['success' => true,
	            					 'data' => $common,
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