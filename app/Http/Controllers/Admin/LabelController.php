<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ListingLabel;
use Validator;
use App\GeneralSetting;
use App\Traits\CheckPermission;
use Illuminate\Support\Facades\Auth;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class LabelController extends Controller
{
    use CheckPermission;
    Public function create()
	{
    $module_name=$this->permission(Auth::user()->id);
		return view('admin.label.add',compact('module_name'));
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
      $module_name=$this->permission(Auth::user()->id);
      $general_setting= GeneralSetting::where('id','=',1)->first();
    	$listing_label=ListingLabel::orderBy('id','DESC')->paginate($general_setting->pagination_value);
    	return view('admin.label.index',compact('listing_label','module_name'));
    }
     public function edit($id)
    {
      $module_name=$this->permission(Auth::user()->id);
    	$label_edit=ListingLabel::where('id',$id)->first();
    	return view('admin.label.edit',compact('label_edit','module_name'));
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
        if($data=="active")
       {
        $data=ListingLabel::whereIn('id', $id)
       ->update(['status' => '1']);
       return response()->json(array('message' => 'success'));
       }
       else if($data=="inactive")
       {
        $data=ListingLabel::whereIn('id', $id)
       ->update(['status' => '0']);
       return response()->json(array('message' => 'success'));
       }
       else
       {
       	  $data=\App\ListingLabel::whereIn('id',$id)->delete();
          return response()->json(array('message' => 'success'));
       
       }
    }
   public function download(Request $request)
    {
      try
      {
       $writer = WriterEntityFactory::createXLSXWriter(Type::XLSX);
       $writer->openToBrowser('Label'.date('Y-m-d:hh:mm:ss').'.xlsx');
       $listing_labels=ListingLabel::get();
       $column = [
              WriterEntityFactory::createCell('Label Id'),
              WriterEntityFactory::createCell('Label Name'),
              WriterEntityFactory::createCell('Status'),
          ];
        $singleRow = WriterEntityFactory::createRow($column);
        $writer->addRow($singleRow);
        foreach ($listing_labels as $key => $listing_label) 
              {
                $cells = [
                WriterEntityFactory::createCell($listing_label->id),
                WriterEntityFactory::createCell($listing_label->label_name),
                WriterEntityFactory::createCell($listing_label->status),
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
