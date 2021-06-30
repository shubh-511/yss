<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ListingCategory;
use Validator;
use URL;
use App\GeneralSetting;
use App\Traits\CheckPermission;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
  use CheckPermission;
	Public function create()
	{
    $module_name=$this->permission(Auth::user()->id);
		return view('admin.category.add','module_name');
	}
   
	Public function save( Request $request)
	{
		try{
		$validator = Validator::make($request->all(), [ 
            'category_name' => 'required',
        ]);

        if ($validator->fails()) 
        { 
            return redirect()->back()->with('err_message',$validator->messages()->first());
        }
            $category_data = new ListingCategory;
            $category_data->category_name = $request->category_name;
            $category_data->status = $request->status;
            $category_data->save();
		   return redirect('login/category')->with('success','Category added successfully');
		}
		catch(\Exception $e)
        {
            return redirect()->back()->with('err_message','Something went wrong!');
        }
	}
    Public function categorylist()
    {
      $module_name=$this->permission(Auth::user()->id);
      $general_setting= GeneralSetting::where('id','=',1)->first();
    	$listing_category=ListingCategory::orderBy('id','DESC')->paginate($general_setting->pagination_value);
    	return view('admin.category.index',compact('listing_category','module_name'));
    }
    Public function edit($id)
    {
      $module_name=$this->permission(Auth::user()->id);
    	$category_edit=ListingCategory::where('id',$id)->first();
    	return view('admin.category.edit',compact('category_edit','module_name'));
    }
    Public function update(Request $request, $id)
    {
    	$validator = Validator::make($request->all(), [ 
            'category_name' => 'required',
        ]);

        if ($validator->fails()) 
        { 
            return redirect()->back()->with('err_message',$validator->messages()->first());
        }
    	   $category = ListingCategory::find($id);
           $category->category_name = $request->category_name;
           $category->status = $request->status;
           $category->save();
           return redirect('login/category')->with('success', 'Category successfully updated');
        
    }
    public function destroy(Request $request, $id='')
    {
        ListingCategory::where('id', $request->id)->delete();
        return redirect('login/category')->with('success','Category deleted successfully');
    }
     public function bulkaction(Request $request)
    {
       $data=$request['action'];
       $id=$request['id'];
       if($data=="active")
       {
        $data=ListingCategory::whereIn('id', $id)
       ->update(['status' => '1']);
       return response()->json(array('message' => 'success'));
       }
       else if($data=="inactive")
       {
        $data=ListingCategory::whereIn('id', $id)
       ->update(['status' => '0']);
       return response()->json(array('message' => 'success'));
       }
       else
       {
        $data=\App\ListingCategory::whereIn('id',$id)->delete();
          return response()->json(array('message' => 'success'));
       }
    }
}
