<?php

namespace App\Http\Controllers\API;

use App\GrantAccessToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class GrantAccessTokenController extends Controller
{
    public $successStatus =200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            
            $keys = GrantAccessToken::select()->get();
            return response()->json(['success' => true,
                                     'keys' => $keys,
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
     * @param  \App\GrantAccessKey  $grantAccessKey
     * @return \Illuminate\Http\Response
     */
    public function show(GrantAccessKey $grantAccessKey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GrantAccessKey  $grantAccessKey
     * @return \Illuminate\Http\Response
     */
    public function edit(GrantAccessKey $grantAccessKey)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GrantAccessKey  $grantAccessKey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GrantAccessKey $grantAccessKey)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GrantAccessKey  $grantAccessKey
     * @return \Illuminate\Http\Response
     */
    public function destroy(GrantAccessKey $grantAccessKey)
    {
        //
    }

    /**
     * Verify Access Key
     *
     * @param  \App\GrantAccessKey  $grantAccessKey
     * @return \Illuminate\Http\Response
     */
    public function verifyAccessToken(Request $request){
        try{

            /*
             * Validation input data
             * @site_key
             * @site_url
            */

            $input = $request->all();

            $validator = Validator::make($input, [ 
                'site_url' => 'required | max:190', 
                'site_key' => 'required | max:26'
            ]);

            if ($validator->fails()) { 
                return response()->json(['errors'=>$validator->errors(),'success' => false], $this->successStatus);
            }

            /*
             * Checking input data
             * @site_key
             * @site_url
            */
            $accessKey = GrantAccessToken::where(['site_key' => $input['site_key'],
                                                  'site_url' => $input['site_url']
                                                ])->first();
            if($accessKey){

                /*
                 * Checking site_key is already verified or not
                 * @site_key
                */
                if($accessKey['verified'] == 1){

                    return response()->json(['msg'=> ['verification_verified' => ['Site key and Site Url has already verified']],'success' => true]);
                }else{

                    /*
                     * site_key and site_url is valid
                     * udpate verified status to 1
                    */
                    
                    GrantAccessToken::where(['id' => $accessKey['id']])->update(['verified' => '1']);

                    return response()->json(['msg'=> ['verification_verified' => ['Site key and Site Url has been verified']],'success' => true]);
                }
            }else{
                return response()->json(['error'=> ['verification_failed' => ['Site key or Site Url is not correct']],'success' => false]); 
            }

        }catch(\Exception $e){
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        }
    }
}
