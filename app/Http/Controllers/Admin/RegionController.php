<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ListingRegion;
use Validator;
use App\GeneralSetting;
use App\Traits\CheckPermission;
use Illuminate\Support\Facades\Auth;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;

class RegionController extends Controller
{
  use CheckPermission;
	Public function create()
	{
    $module_name=$this->permission(Auth::user()->id);
		return view('admin.region.add',compact('module_name'));
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
      $module_name=$this->permission(Auth::user()->id);
      $general_setting= GeneralSetting::where('id','=',1)->first();
    	$listing_region=ListingRegion::orderBy('id','DESC')->paginate($general_setting->pagination_value);
    	return view('admin.region.index',compact('listing_region','module_name'));
    }
     public function edit($id)
    {
      $module_name=$this->permission(Auth::user()->id);
    	$region_edit=ListingRegion::where('id',$id)->first();
    	return view('admin.region.edit',compact('region_edit','module_name'));
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
  public function download(Request $request)
    {
      try
      {
       $writer = WriterEntityFactory::createXLSXWriter(Type::XLSX);
       $writer->openToBrowser('Region'.date('Y-m-d:hh:mm:ss').'.xlsx');
       $listing_regions=ListingRegion::get();
       $column = [
              WriterEntityFactory::createCell('Region Id'),
              WriterEntityFactory::createCell('Region Name'),
              WriterEntityFactory::createCell('Status'),
          ];
            $singleRow = WriterEntityFactory::createRow($column);
            $writer->addRow($singleRow);
           foreach ($listing_regions as $key => $listing_region) 
              {
                $cells = [
                WriterEntityFactory::createCell($listing_region->id),
                WriterEntityFactory::createCell($listing_region->region_name),
                WriterEntityFactory::createCell($listing_region->status),
             ];
              $singleRow = WriterEntityFactory::createRow($cells);
              $writer->addRow($singleRow); 
             }
          $writer->close();
          exit();
        }
        catch(\Exception $e)
        {
            return redirect()->back()->with('err_message','Something went wrong!');
        }
    }
}
