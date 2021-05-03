<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\listingCategory;
use Validator;

class CategoryController extends Controller
{
	Public function create()
	{
		return view('admin.category.add');
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
            $category_data = new listingCategory;
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
    	$listing_category=listingCategory::orderBy('id','DESC')->paginate(25);
    	return view('admin.category.index',compact('listing_category'));
    }
    Public function edit($id)
    {
    	$category_edit=listingCategory::where('id',$id)->first();
    	return view('admin.category.edit',compact('category_edit'));
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
    	   $category = listingCategory::find($id);
           $category->category_name = $request->category_name;
           $category->status = $request->status;
           $category->save();
           return redirect('login/category')->with('success', 'Category successfully updated');
        
    }
    public function destroy(Request $request, $id='')
    {
        listingCategory::where('id', $request->id)->delete();
        return redirect('login/category')->with('success','Category deleted successfully');
    }
     public function bulkaction(Request $request)
    {
       $data=$request['action'];
       $id=$request['id'];
       if($data=="delete")
       {
       	  $data=\App\listingCategory::whereIn('id',$id)->delete();
          return response()->json(array('message' => 'success'));
       
       }
    }
}
