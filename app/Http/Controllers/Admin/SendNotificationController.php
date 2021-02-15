<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use App\User; 
use App\Notification;
use Validator;
use Event;

class SendNotificationController extends Controller
{
    public $successStatus = 200;
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendNotification(Request $request)
    {
    	$users = User::where('role_id','!=',1)->get();
    	return view('admin.notification.send_notification',compact('users'));
    }


   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user' => 'required', 
            'title' => 'required',
            'body' => 'required',
        ]);

        if ($validator->fails()) 
        { 
            return redirect()->back()->with('err_message',$validator->messages()->first());
        }

        $notif = new Notification;
        $notif->sender = 1;
        $notif->receiver = $request->user;
        $notif->title = $request->title;
        $notif->body = $request->body;
        $notif->save();


        return redirect('login/send-notification')->with('success','Notification sent successfully');
    }

}