<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use Validator;
use App\GeneralSetting;
use App\Module;
use App\RoleModule;
use App\Traits\CheckPermission;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
  use CheckPermission;
    public function role()
    {
        $module_name=$this->permission(Auth::user()->id);
        $general_setting= GeneralSetting::where('id','=',1)->first();
        $role_data=Role::orderBy('id','DESC')->paginate($general_setting->pagination_value);
        return view('admin.role.index',compact('role_data','module_name'));
    }
    public function createRole(Request $request)
    {
        $module_name=$this->permission(Auth::user()->id);
        return view('admin.role.add',compact('module_name'));
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
            $module_name=$this->permission(Auth::user()->id); 
            $edit_role=Role::where('id',$id)->first();
            return view('admin.role.edit',compact('edit_role','module_name'));

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
    public function rolePrivilege()
    {
      $module_name=$this->permission(Auth::user()->id);
       $general_setting= GeneralSetting::where('id','=',1)->first();
       $r_module=Module::orderBy('id','DESC')->get();
       $r_role=Role::orderBy('id','DESC')->get();
       $module_permission=RoleModule::get();
       $module_arr = array();
         foreach($module_permission as $permission)
          {
            $module_arr[] = $permission->role_id;
          }
           $unique_data_role = array_unique($module_arr);
           $module_id=RoleModule::whereIn('role_id',$unique_data_role)->get();
           return view('admin.privilege.index',compact('r_module','r_role','unique_data_role','module_id','module_name'));  
    }
    public function savePrivilege(Request $request)
    {
      $module_name=$this->permission(Auth::user()->id);
      $role_data=Role::whereNotIn('role',['counsellor','user'])->get();
      $module_data=Module::get();
      return view('admin.privilege.add',compact('role_data','module_data','module_name'));
    }
    public function storePrivilege(Request $request)
    {
        try
        {
           foreach ($request->module as $module) 
            {
              $module_data=RoleModule::where('role_id',$request->role)->where('module_id',$module)->first();
                if(empty($module_data))
                {
                  $module_data=new RoleModule;
                  $module_data->role_id=$request->role;
                  $module_data->module_id=$module;
                  $module_data->save();

                }
            }
            return redirect('login/role/privilege')->with('success','Module added successfully');
        }
       catch(\Exception $e)
        {
         return redirect()->back()->with('err_message','Something went wrong!');
        }
    }
    public function editPrivilege($id)
    {
      $module_name=$this->permission(Auth::user()->id);
      $role_data=Role::whereNotIn('role',['counsellor','user'])->get();
      $role_module_data=RoleModule::where('role_id',$id)->get();
      $role_id=RoleModule::where('role_id',$id)->first();
      $module_data=Module::get();
      return view('admin.privilege.edit',compact('role_data','role_module_data','module_name','module_data','role_id'));
    }
    public function updatePrivilege(Request $request)
    {
      try
      {
       $role_id=$request->role;
       $module_id=$request->module;
       $role_module_data=RoleModule::where('role_id',$role_id)->get();
       foreach ($role_module_data as $data)
        {
          $data->delete();
        }
        foreach ($module_id as $module) 
            {
              $module_data=RoleModule::where('role_id',$role_id)->where('module_id',$module)->first();
                if(empty($module_data))
                {
                  $module_data=new RoleModule;
                  $module_data->role_id=$request->role;
                  $module_data->module_id=$module;
                  $module_data->save();

                }
            }
            return redirect('login/role/privilege')->with('success','Module updated successfully');
      }
       catch(\Exception $e)
        {
         return redirect()->back()->with('err_message','Something went wrong!');
        }
       
    }
    public function destroy(Request $request)
    {
      RoleModule::whereIn('role_id', [$request->id])->delete();
     return redirect('login/role/privilege')->with('success','Module deleted successfully');

    }

}
