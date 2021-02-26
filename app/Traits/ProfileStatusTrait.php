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
//use App\Events\UserRegisterEvent;

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

        if($packagePerct == 0 && $avalPerct == 0 && $stripePerct == 0)
        {
            $profilePercentage = "25";
        }
        elseif($packagePerct > 0 && $avalPerct == 0 && $stripePerct == 0)
        {
            $profilePercentage = "50";
        }
        elseif($packagePerct == 0 && $avalPerct > 0 && $stripePerct == 0)
        {
            $profilePercentage = "50";
        }
        elseif($packagePerct == 0 && $avalPerct == 0 && $stripePerct > 0)
        {
            $profilePercentage = "50";
        }
        elseif(($packagePerct > 0 && $avalPerct > 0 ) && $stripePerct == 0)
        {
            $profilePercentage = "75";
        }
        elseif($packagePerct == 0 && ($avalPerct > 0 && $stripePerct > 0))
        {
            $profilePercentage = "75";
        }
        elseif($avalPerct == 0 && ($packagePerct > 0 && $stripePerct > 0))
        {
            $profilePercentage = "75";
        }
        else
        {
            $profilePercentage = "100";
            //Send Mail
            $existNotif = Notification::where('receiver', $userId)->where('title', 'Profile Completed')->first();
            if(empty($existNotif))
            {
                $newNotif = new Notification;
                $newNotif->receiver = $userId;
                $newNotif->title = "Profile Completed";
                $newNotif->body = "Your profile has been completed!";
                $newNotif->save();
            }
            
            
            //event(new ProfileCompleteEvent($user->id));
            //$this->sendProfileCompletionSMS($user->country_code, $user->phone);
        }
        $userUpdate = User::where('id', $userId)->update(['profile_percentage' => $profilePercentage]);

        return $profilePercentage;
    }


}
