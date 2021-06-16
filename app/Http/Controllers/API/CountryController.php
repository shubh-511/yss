<?php

namespace App\Http\Controllers\API;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Booking;
use App\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class CountryController extends Controller
{
    public $successStatus = 200;    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            
            $countries = Country::select('id','name','phonecode')->orderBy('name','asc')->get();
            return response()->json(['success' => true,
                                     'countries' => $countries,
                                    ], $this->successStatus); 

         }catch(\Exception $e){
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus);
         }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        //
    }
  public function calender(Request $request)
   {
    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig(storage_path('keys/c.json'));
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    $accessToken = $request->google_token;
    $user=Auth::user();
    $user_data=User::where('id',$user->id)->first();
    $user_data->google_token=$accessToken;
    $user_data->save();
    $booking_data=Booking::where('user_id',$user->id)->get();
    if(!empty($user_data->google_token))
    {
    $client->setAccessToken($user_data->google_token,true);
    $service = new Google_Service_Calendar($client);
    $calendarId = 'primary';
    $optParams = array(
      'maxResults' => 10,
      'orderBy' => 'startTime',
      'singleEvents' => true,
      'timeMin' => date('c'),
    ); 
    
      $results = $service->events->listEvents($calendarId, $optParams);
      
      $events = $results->getItems();

      if (empty($events)) {
          print "No upcoming events found.\n";
      } else {
          print "Upcoming events:\n";
          foreach ($events as $event) {
              $start = $event->start->dateTime;
              if (empty($start)) {
                  $start = $event->start->date;
              }
              printf("%s (%s)\n", $event->getSummary(), $start);
          }
      }
  }
}

}
