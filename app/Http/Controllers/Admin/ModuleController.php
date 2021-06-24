<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Module;
use Validator;
use App\GeneralSetting;

class ModuleController extends Controller
{
	public function module()
    {
    	$general_setting= GeneralSetting::where('id','=',1)->first();
        $module_data=Module::orderBy('id','DESC')->paginate($general_setting->pagination_value);
        return view('admin.module.index',compact('module_data'));
    }
    public function createModule()
    {
    	 return view('admin.module.add');
    }

   public function saveModule(Request $request)
   {
       	try
       	{
     	  $validator =  Validator::make($request->all(), [
            'module_name' => 'required|unique:modules,module_name'
            ]);

            if ($validator->fails()) 
            {
             return redirect()->back()->withErrors($validator)->withInput();
            }
            $module_data=new Module();
            $module_data->module_name=$request->module_name;
            $module_data->save();
            if($module_data)
            {
              return redirect('login/module')->with('success','Module added successfully');
            }
        }
        catch(\Exception $e)
        {
         return redirect()->back()->with('err_message','Something went wrong!');
        }
    }
    public function editModule($id)
    {
            $edit_module=Module::where('id',$id)->first();
            return view('admin.module.edit',compact('edit_module'));

    }
    public function updateModule(Request $request, $id)
    {
     try{
	    	$validator =  Validator::make($request->all(), [
	            'module_name' => 'required|unique:modules,module_name'
	            ]);

	        if ($validator->fails()) 
	        {
             return redirect()->back()->withErrors($validator)->withInput();
	        }
	        $update_module=Module::find($id);
	        $update_module->module_name=$request->module_name;
	        $update_module->save();
	        if($update_module)
	        {
	         return redirect('login/module')->with('success','Module updated successfully');
	        }
	    }
	    catch(\Exception $e)
        {
         return redirect()->back()->with('err_message','Something went wrong!');
        }
    }
    
}
