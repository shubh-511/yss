<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; 
use App\Notification;
use App\Package;
use App\Booking;
use App\Availability;
use App\StripeConnect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Events\ProfileCompleteEvent;

trait ProfileStatusTrait
{
    
    /***
    Get Profile Status
    ***/
    public function profileStatus($userId)
    {
        $profilePercentage = '';
        
        $packagePerct = Package::where('user_id', $userId)->count();
        $avalPerct = Availability::where('user_id', $userId)->count();
        $stripePerct = StripeConnect::where('user_id', $userId)->count();

        $user = User::where('id', $userId)->first();
        $userTimezone = $user->timezone;
        if(!empty($userTimezone))
        {
            $userTimezone = 1;
        }
        else
        {
            $userTimezone = 0;
        }

        if($packagePerct == 0 && $avalPerct == 0 && $stripePerct == 0 && $userTimezone == 0)  
        {
            $profilePercentage = "25";
        }
        elseif($packagePerct > 0 && $avalPerct == 0 && $stripePerct == 0 && $userTimezone == 0)
        {
            $profilePercentage = "50";
        }
        elseif($packagePerct == 0 && $avalPerct > 0 && $stripePerct == 0 && $userTimezone == 0)
        {
            $profilePercentage = "50";
        }
        elseif($packagePerct == 0 && $avalPerct == 0 && $stripePerct > 0 && $userTimezone == 0)
        {
            $profilePercentage = "40";
        }
        elseif($packagePerct == 0 && $avalPerct == 0 && $stripePerct == 0 && $userTimezone > 0)
        {
            $profilePercentage = "35";
        }/*****/

        /****/
        elseif(($packagePerct > 0 && $avalPerct > 0 ) && $stripePerct == 0 && $userTimezone == 0)
        {
            $profilePercentage = "75";
        }
        elseif($packagePerct == 0 && $avalPerct == 0 && ($stripePerct > 0 && $userTimezone > 0))
        {
            $profilePercentage = "50";
        }
        elseif($packagePerct == 0 && ($stripePerct > 0 && $avalPerct > 0) && $userTimezone == 0)
        {
            $profilePercentage = "65";
        }
        elseif($packagePerct > 0 && ($stripePerct == 0 && $avalPerct == 0) && $userTimezone > 0)
        {
            $profilePercentage = "60";
        }
        elseif($packagePerct == 0 && $stripePerct == 0 && ($avalPerct > 0 && $userTimezone > 0))
        {
            $profilePercentage = "60";
        }

        /****/

        /****/
        elseif(($packagePerct > 0 && $stripePerct > 0 && $avalPerct > 0) && $userTimezone == 0)
        {
            $profilePercentage = "90";
        }
        elseif(($userTimezone > 0 && $stripePerct > 0 && $avalPerct > 0) && $packagePerct == 0)
        {
            $profilePercentage = "75";
        }
        elseif(($userTimezone > 0 && $packagePerct > 0 && $avalPerct > 0) && $stripePerct == 0)
        {
            $profilePercentage = "85";
        }
        elseif(($userTimezone > 0 && $packagePerct > 0 && $stripePerct > 0) && $avalPerct == 0)
        {
            $profilePercentage = "75";
        }/****/

        /****/
        else
        {
            $profilePercentage = "100";
            //Send Mail
            $existNotif = Notification::where('receiver', $userId)->where('title', 'Profile Completed')->first();
            if(empty($existNotif))
            {
                $newNotif = new Notification;
                $newNotif->receiver = $userId;
                $time=Carbon::now($user->timezone)->toDateTimeString();
                $newNotif->created_at=$time;
                $newNotif->title = "Profile Completed";
                $newNotif->body = "Your profile has been completed!";
                $newNotif->save();
            }
            
            
            event(new ProfileCompleteEvent($user->id));
            $this->sendProfileCompletionSMS($user->country_code, $user->phone);
        }
        $userUpdate = User::where('id', $userId)->update(['profile_percentage' => $profilePercentage]);

        return $profilePercentage;
    }


}
