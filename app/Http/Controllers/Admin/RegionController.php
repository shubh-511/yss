<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ListingRegion;
use Validator;

class RegionController extends Controller
{
	Public function create()
	{
		return view('admin.region.add');
	}
	Public function save( Request $request)
	{
		try{
		$validator = Validator::make($request->all(), [ 
            'region_name' => 'required',
        ]);

        if ($validator->fails()) 
        { 
            return redirect()->back()->with('err_message',$validator->messages()->first());
        }
            $region_data = new ListingRegion;
            $region_data->region_name = $request->region_name;
            $region_data->status = $request->status;
            $region_data->save();
		   return redirect('login/region')->with('success','Region added successfully');
		}
		catch(\Exception $e)
        {
            return redirect()->back()->with('err_message','Something went wrong!');
        }
	}
     Public function regionlist()
    {
    	$listing_region=ListingRegion::orderBy('id','DESC')->paginate(25);
    	return view('admin.region.index',compact('listing_region'));
    }
     public function edit($id)
    {
    	$region_edit=ListingRegion::where('id',$id)->first();
    	return view('admin.region.edit',compact('region_edit'));
    }
    Public function update(Request $request, $id)
    {
    	$validator = Validator::make($request->all(), [ 
            'region_name' => 'required',
        ]);

        if ($validator->fails()) 
        { 
            return redirect()->back()->with('err_message',$validator->messages()->first());
        }
    	   $category = ListingRegion::find($id);
           $category->region_name = $request->region_name;
           $category->status = $request->status;
           $category->save();
           return redirect('login/region')->with('success', 'Region successfully updated');
        
    }
     public function destroy(Request $request, $id='')
    {
        ListingRegion::where('id', $request->id)->delete();
        return redirect('login/region')->with('success','Region deleted successfully');
    }
     public function bulkaction(Request $request)
    {
       $data=$request['action'];
       $id=$request['id'];
        if($data=="active")
       {
        $data=ListingRegion::whereIn('id', $id)
       ->update(['status' => '1']);
       return response()->json(array('message' => 'success'));
       }
       else if($data=="inactive")
       {
        $data=ListingRegion::whereIn('id', $id)
       ->update(['status' => '0']);
       return response()->json(array('message' => 'success'));
       }
       else
       {
       	  $data=\App\ListingRegion::whereIn('id',$id)->delete();
          return response()->json(array('message' => 'success'));
       
       }
    }
}
