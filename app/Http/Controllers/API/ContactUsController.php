<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Contact; 
use App\Events\ContactEvent;
use Event;

class ContactUsController extends Controller
{
   public $successStatus = 200;
   public function contactUs(Request $request)
   {
     try
       {
         $validator = Validator::make($request->all(), [ 
                'name' => 'required|max:190',
                'email' => 'required|max:190|email',  
                'subject'=>'required',
                'message'=>'required',  
                
            ]);

          if ($validator->fails())
            { 
              return response()->json(['errors'=>$validator->errors()], $this->successStatus);
            }
            $input = $request->all();
            $input['name'] = $input['name'];
            $input['email'] = $input['email'];
            $input['phone'] = $input['subject']; 
            $input['message'] = $input['message']; 
            $contact = Contact::create($input); 
            event(new ContactEvent($contact->id));
            return response()->json(['success' => true,
                                     'contact' => $contact,
                                    ], $this->successStatus); 
        }
        catch(\Exception $e){
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
   }
}
