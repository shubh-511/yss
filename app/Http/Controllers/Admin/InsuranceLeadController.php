<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InsuranceLead;

class InsuranceLeadController extends Controller
{
    public function insurancelist(Request $request)
    {
    	 $insurance_data=InsuranceLead::where(function ($query) use($request) {
    	 	 if ($request->get('name') != null)
          {
	         $query->where('first_name', 'like', '%' . $request->get('name') . '%')
	         ->orwhere('last_name', 'like', '%' . $request->get('name') . '%')
	        ->orwhere('email', 'like', '%' . $request->get('name') . '%');
	      }
         })->orderBy('id','DESC')->paginate(25);
    	return view('admin.insurance.index',compact('insurance_data'));
    }
    public function bulkaction(Request $request)
    {
       $data=$request['action'];
       $id=$request['id'];
       if($data=="delete")
       {
       	  $data=\App\InsuranceLead::whereIn('id',$id)->delete();
          return response()->json(array('message' => 'success'));
       
       }
    }
    public function destroy(Request $request, $id='')
    {
        InsuranceLead::where('id', $request->id)->delete();
        return redirect('login/insurance')->with('success','Insurance user deleted successfully');
    }
    public function view($id)
    {
    	$insurance = InsuranceLead::where('id',$id)->first();
    	return view('admin.insurance.detail',compact('insurance'));
    }
}
