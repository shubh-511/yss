<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ListingLabel;
use Validator;

class LabelController extends Controller
{
    Public function create()
	{
		return view('admin.label.add');
	}
	Public function save( Request $request)
	{
		try{
		$validator = Validator::make($request->all(), [ 
            'label_name' => 'required',
        ]);

        if ($validator->fails()) 
        { 
            return redirect()->back()->with('err_message',$validator->messages()->first());
        }
            $label_data = new ListingLabel;
            $label_data->label_name = $request->label_name;
            $label_data->status = $request->status;
            $label_data->save();
		   return redirect('login/label')->with('success','Label added successfully');
		}
		catch(\Exception $e)
        {
            return redirect()->back()->with('err_message','Something went wrong!');
        }
	}
     Public function labellist()
    {
    	$listing_label=ListingLabel::orderBy('id','DESC')->paginate(25);
    	return view('admin.label.index',compact('listing_label'));
    }
     public function edit($id)
    {
    	$label_edit=ListingLabel::where('id',$id)->first();
    	return view('admin.label.edit',compact('label_edit'));
    }
    Public function update(Request $request, $id)
    {
    	$validator = Validator::make($request->all(), [ 
            'label_name' => 'required',
        ]);

        if ($validator->fails()) 
        { 
            return redirect()->back()->with('err_message',$validator->messages()->first());
        }
    	   $category = ListingLabel::find($id);
           $category->label_name = $request->label_name;
           $category->status = $request->status;
           $category->save();
           return redirect('login/label')->with('success', 'Label successfully updated');
        
    }
     public function destroy(Request $request, $id='')
    {
        ListingLabel::where('id', $request->id)->delete();
        return redirect('login/label')->with('success','Label deleted successfully');
    }
     public function bulkaction(Request $request)
    {
       $data=$request['action'];
       $id=$request['id'];
       if($data=="delete")
       {
       	  $data=\App\ListingLabel::whereIn('id',$id)->delete();
          return response()->json(array('message' => 'success'));
       
       }
    }
}
