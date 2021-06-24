<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use Validator;
use App\GeneralSetting;

class RoleController extends Controller
{
    public function role()
    {
        $general_setting= GeneralSetting::where('id','=',1)->first();
        $role_data=Role::orderBy('id','DESC')->paginate($general_setting->pagination_value);
        return view('admin.role.index',compact('role_data'));
    }
    public function createRole(Request $request)
    {
        return view('admin.role.add');
    }
   public function saveRole(Request $request)
   {
       	try
       	{
     	  $validator =  Validator::make($request->all(), [
            'name' => 'required',
            'role' => 'required|unique:roles,role'
            ]);

            if ($validator->fails()) 
            {
             return redirect()->back()->withErrors($validator)->withInput();
            }
            $role_data=new Role();
            $role_data->name=$request->name;
            $role_data->role=$request->role;
            $role_data->save();
            if($role_data)
            {
              return redirect('login/role')->with('success','Role added successfully');
            }
        }
        catch(\Exception $e)
        {
         return redirect()->back()->with('err_message','Something went wrong!');
        }
    }
    public function editRole($id)
    {
            $edit_role=Role::where('id',$id)->first();
            return view('admin.role.edit',compact('edit_role'));

    }
    public function updateRole(Request $request, $id)
    {
     try{
	    	$validator =  Validator::make($request->all(), [
	            'name' => 'required',
	            'role' => 'required|unique:roles,role'
	            ]);

	        if ($validator->fails()) 
	        {
             return redirect()->back()->withErrors($validator)->withInput();
	        }
	        $update_role=Role::find($id);
	        $update_role->name=$request->name;
	        $update_role->role=$request->role;
	        $update_role->save();
	        if($update_role)
	        {
	         return redirect('login/role')->with('success','Role updated successfully');
	        }
	    }
	    catch(\Exception $e)
        {
         return redirect()->back()->with('err_message','Something went wrong!');
        }
    }
}
