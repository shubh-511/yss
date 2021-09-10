<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\GeneralSetting;

class SubAdminController extends Controller
{
    public function subAdmin(Request $request)
    {
        $general_setting= GeneralSetting::where('id','=',1)->first();
        $subAdmin=User::whereNotIn('role_id', [1,2,3])->orderBy('id','DESC')->paginate($general_setting->pagination_value);;
         return view('admin.subadmin.index',compact('subAdmin'));
    }
    public function destroy(Request $request)
    {
        User::where('id', $request->id)->delete();
        return redirect('login/sub/admin')->with('success','subAdmin deleted successfully');
    }
    public function bulk(Request $request)
    {
         $id=$request->id;
         $data=\App\User::whereIn('id',$id)->delete();
          return response()->json(array('message' => 'success'));
    }
    public function edit($id)
    {
         $edit_subadmin=User::where('id', $id)->first();
         return view('admin.subadmin.edit',compact('edit_subadmin'));

    }
    public function update(Request $request,$id)
    {
        $update_subadmin=User::where('id', $id)->first();
        $update_subadmin->name=$request->name;
        $update_subadmin->timezone=$request->timezone;
        $update_subadmin->save();
        return redirect('login/sub/admin/')->with('success','subAdmin updated successfully');
    }
}
