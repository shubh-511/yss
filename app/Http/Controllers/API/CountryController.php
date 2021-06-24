<?php

namespace App\Http\Controllers\API;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
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
    //$client->setAccessType('offline');
    $client->setPrompt('select_account consent');


    $accessToken = $request->google_token;
    $user=Auth::user();
    $user_data=User::where('id',$user->id)->first();
    $user_data->google_token=$accessToken;
    $user_data->save();
    $booking_data=Booking::where('user_id',$user->id)->get();
//$fetched = $client->fetchAccessTokenWithAuthCode($accessToken);
//dd($fetched);
    if(!empty($user_data->google_token))
    {

    $client->setAccessToken($user_data->google_token,true);
    $client->createAuthUrl();
    //dd($client);
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
dd ($results);

      if (empty($events)) 
      {
        $ev = new Google_Service_Calendar_Event(array(
  'summary' => 'Google I/O 2015',
  'location' => '800 Howard St., San Francisco, CA 94103',
  'description' => 'A chance to hear more about Google\'s developer products.',
  'start' => array(
    'dateTime' => '2015-05-28T09:00:00-07:00',
    'timeZone' => 'America/Los_Angeles',
  ),
  'end' => array(
    'dateTime' => '2015-05-28T17:00:00-07:00',
    'timeZone' => 'America/Los_Angeles',
  ),
  'recurrence' => array(
    'RRULE:FREQ=DAILY;COUNT=2'
  ),
  'attendees' => array(
    array('email' => 'lpage@example.com'),
    array('email' => 'sbrin@example.com'),
  ),
  'reminders' => array(
    'useDefault' => FALSE,
    'overrides' => array(
      array('method' => 'email', 'minutes' => 24 * 60),
      array('method' => 'popup', 'minutes' => 10),
    ),
  ),
));

$calendarId = 'primary';
$evs = $service->events->insert($calendarId, $ev);
dd($ev);
//printf('Event created: %s\n', $evs->htmlLink);
          //print "No upcoming events found.\n";
      } else 
        {
          print "Upcoming events:\n";
          foreach ($events as $event) 
           {
              $start = $event->start->dateTime;
              if (empty($start)) 
               {
                  $start = $event->start->date;
               }
              printf("%s (%s)\n", $event->getSummary(), $start);

          }
      }
  }
}

     /* public function calender(Request $request)
       {
            $client = new Google_Client();
            $client->setApplicationName('Google Calendar API PHP Quickstart');
            $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
            $client->setAuthConfig(storage_path('keys/client.json'));
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');
            $accessToken = $request->google_token;
            $user=Auth::user();
            $user_data=User::where('id',$user->id)->first();
            $user_data->google_token=$accessToken;
            $user_data->save();
            $client->setAccessToken($user_data->google_token,true);
            // If there is no previous token or it's expired.
            if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            $newtoken=$client->getAccessToken();
            }
            return $client;
        }*/



}
