<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Booking;
use Illuminate\Support\Facades\Auth; 
use PDF;
use Carbon\Carbon;
use App\Package;
use App\Payment;
use App\User; 
use DB;
use App\GeneralSetting;
use App\Traits\CheckPermission;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class TransactionController extends Controller
{
	   use CheckPermission;
	   public $successStatus = 200;
	    public function transactionlist(Request $request)
	    {
	    	$module_name=$this->permission(Auth::user()->id);
	    	$general_setting= GeneralSetting::where('id','=',1)->first();
	    	$counsellor_data= User::where('role_id','2')->get();
	    	$payment_data= Payment::get();
	        $bookings = Booking::with('payment_detail')->where(function ($query) use ($request)
	        {
	        	 if ($request->get('counsellor') != null && $request->get('from_date') != null && $request->get('to_date') != null) 
                {
                   
  	                 $query->where('counsellor_id','=',$request->counsellor)->where('booking_date', '>=', $request->get('from_date'))->where('booking_date','<=', $request->get('to_date'));
	                
                }
            	 if ($request->get('counsellor') != null && $request->get('from_date') != null) 
                {
                   
  	                 $query->where('counsellor_id','=',$request->counsellor)->where('booking_date', '>=', $request->get('from_date'));
	                
                }
                 if ($request->get('counsellor') != null && $request->get('to_date') != null) 
                {
                   
  	                echo "<script>";
					echo "alert('Please select from date');";
					echo "</script>";
	                
                }
	           if ($request->get('from_date') != null && $request->get('to_date') != null)
                {
                  $query->where('booking_date', '>=', $request->get('from_date'))
                  ->where('booking_date','<=', $request->get('to_date'));
                } 	
              if ($request->get('counsellor') != null) 
                { 
			      $query->where('counsellor_id','=',$request->get('counsellor'));
			    }
			   if ($request->get('from_date') != null) 
                { 
                  
                  $query->where('booking_date', '>=', $request->get('from_date'));
                   
			    }
			     if ($request->get('to_date') != null) 
                { 
                  
                    echo "<script>";
					echo "alert('Please select from date');";
					echo "</script>";
                   
			    }
                   })->whereNotIn('payment_id',['0'])->orderBy('id','DESC')->paginate($general_setting->pagination_value);
	               return view('admin.transaction.index',compact('bookings','counsellor_data','payment_data','module_name'))
	            ->with('i', ($request->input('page', 1) - 1) * 5);
	    }
	    
	    public function download(Request $request)
	    {
	      
		      $counsellor=$request->counsellor_id;
		      $from_date=$request->from_date;
		      $to_date=$request->to_date;
		      $writer = WriterEntityFactory::createXLSXWriter(Type::XLSX);
		      $writer->openToBrowser('Revenue-Report'.date('Y-m-d:hh:mm:ss').'.xlsx');
                      $totalCost = 0;
                      $column = [
			         WriterEntityFactory::createCell('Transaction No'),
			         WriterEntityFactory::createCell('Counsellor Name'),
			         WriterEntityFactory::createCell('Package Name'),
			         WriterEntityFactory::createCell('Booking Date'),
			         WriterEntityFactory::createCell('Amount Paid'),
			         WriterEntityFactory::createCell('Total Revenue'),
			       ];
				$singleRow = WriterEntityFactory::createRow($column);
			    $writer->addRow($singleRow);
			    if($counsellor != "" && $from_date != "" && $to_date != "")
		            {
			       $bookings = Booking::with('payment_detail','counsellor','user','package')->where('counsellor_id',$counsellor)->where('booking_date', '>=',$from_date)->where('booking_date', '<=',$to_date)->get();
			        foreach ($bookings->unique('payment_id') as $key => $booking) 
			        {

			       	   $totalCost += $booking['payment_detail']['amount']/100;
			       	   if($booking['payment_detail']['balance_transaction'] == "")
			       	    {
				       	   	$cells = [
							    WriterEntityFactory::createCell("txn_1Ixm1456y009ofleXwVCW2EAdmin"),
							    WriterEntityFactory::createCell($booking['counsellor']['name']),
							    WriterEntityFactory::createCell($booking['package']['package_name']),
							    WriterEntityFactory::createCell($booking['booking_date']),
							    WriterEntityFactory::createCell("0.00"),
							   
							];
							$singleRow = WriterEntityFactory::createRow($cells);
							$writer->addRow($singleRow);
			       	   }
			       	   else
			       	   {
				       	   $cells = [
							    WriterEntityFactory::createCell($booking['payment_detail']['balance_transaction']),
							    WriterEntityFactory::createCell($booking['counsellor']['name']),
							    WriterEntityFactory::createCell($booking['package']['package_name']),
							    WriterEntityFactory::createCell($booking['booking_date']),
							    WriterEntityFactory::createCell(($booking['payment_detail']['amount'])),
							   
							];
							$singleRow = WriterEntityFactory::createRow($cells);
							$writer->addRow($singleRow); 
						}
			       }
			       $total =[
					    WriterEntityFactory::createCell($totalCost),
					];
					$total_revenue = WriterEntityFactory::createRow($total);
					$writer->addRow($total_revenue);
			    }   
	           else if($counsellor != "" && $from_date != "")
		       {
			       $bookings = Booking::with('payment_detail','counsellor','user','package')->where('counsellor_id',$counsellor)->where('booking_date', '>=',$from_date)->get();
			        foreach ($bookings->unique('payment_id') as $key => $booking) 
			        {


			       	   $totalCost += $booking['payment_detail']['amount']/100;
			       	   if($booking['payment_detail']['balance_transaction'] == "")
			       	    {
				       	   	$cells = [
							    WriterEntityFactory::createCell("txn_1Ixm1456y009ofleXwVCW2EAdmin"),
							    WriterEntityFactory::createCell($booking['counsellor']['name']),
							    WriterEntityFactory::createCell($booking['package']['package_name']),
							    WriterEntityFactory::createCell($booking['booking_date']),
							    WriterEntityFactory::createCell("0.00"),
							   
							];
							$singleRow = WriterEntityFactory::createRow($cells);
							$writer->addRow($singleRow);
			       	   }
			       	   else
			       	   {
				       	      $cells = [
							    WriterEntityFactory::createCell($booking['payment_detail']['balance_transaction']),
							    WriterEntityFactory::createCell($booking['counsellor']['name']),
							    WriterEntityFactory::createCell($booking['package']['package_name']),
							    WriterEntityFactory::createCell($booking['booking_date']),
							    WriterEntityFactory::createCell(($booking['payment_detail']['amount'])),
							   
							];
							$singleRow = WriterEntityFactory::createRow($cells);
							$writer->addRow($singleRow); 
					    }
			       }
			       $total =[
					    WriterEntityFactory::createCell($totalCost),
					];
					$total_revenue = WriterEntityFactory::createRow($total);
					$writer->addRow($total_revenue);
			    }   
			    else if($from_date != "" && $to_date != "")
		           {
			       $bookings = Booking::with('payment_detail','counsellor','user','package')->where('booking_date', '>=',$from_date)->where('booking_date', '<=',$to_date)->get();
			        foreach ($bookings->unique('payment_id') as $key => $booking) 
			        {
			        	$totalCost += $booking['payment_detail']['amount']/100;
			        	if($booking['payment_detail']['balance_transaction'] == "")
			       	    {
				       	   	$cells = [
							    WriterEntityFactory::createCell("txn_1Ixm1456y009ofleXwVCW2EAdmin"),
							    WriterEntityFactory::createCell($booking['counsellor']['name']),
							    WriterEntityFactory::createCell($booking['package']['package_name']),
							    WriterEntityFactory::createCell($booking['booking_date']),
							    WriterEntityFactory::createCell("0.00"),
							   
							];
							$singleRow = WriterEntityFactory::createRow($cells);
							$writer->addRow($singleRow);
			       	   }
			       	   else
			       	   {
				       	   $cells = [
							    WriterEntityFactory::createCell($booking['payment_detail']['balance_transaction']),
							    WriterEntityFactory::createCell($booking['counsellor']['name']),
							    WriterEntityFactory::createCell($booking['package']['package_name']),
							    WriterEntityFactory::createCell($booking['booking_date']),
							    WriterEntityFactory::createCell(($booking['payment_detail']['amount'])),
							   
							];
							$singleRow = WriterEntityFactory::createRow($cells);
							$writer->addRow($singleRow);
						} 
			       }
			       $total =[
					    WriterEntityFactory::createCell($totalCost),
					];
					$total_revenue = WriterEntityFactory::createRow($total);
					$writer->addRow($total_revenue);
			    }   


			   else if($counsellor != "" && $to_date != "")
		            {
                    echo "<script>";
				    echo "alert('Please select from date');";
				    echo "</script>";

			   }   
			    
			     else if($counsellor != "")
		           {
			       $bookings = Booking::with('payment_detail','counsellor','user','package')->where('counsellor_id',$counsellor)->get();
			        foreach ($bookings as $key => $booking) 
			        {
			       	   $totalCost += $booking['payment_detail']['amount']/100;
			       	    if($booking['payment_detail']['balance_transaction'] == "")
			       	   {
				       	   	$cells = [
							    WriterEntityFactory::createCell("txn_1Ixm1456y009ofleXwVCW2EAdmin"),
							    WriterEntityFactory::createCell($booking['counsellor']['name']),
							    WriterEntityFactory::createCell($booking['package']['package_name']),
							    WriterEntityFactory::createCell($booking['booking_date']),
							    WriterEntityFactory::createCell("0.00"),
							   
							];
							$singleRow = WriterEntityFactory::createRow($cells);
							$writer->addRow($singleRow);
			       	   }
			       	   else
			       	   {
				       	   $cells = [
							    WriterEntityFactory::createCell($booking['payment_detail']['balance_transaction']),
							    WriterEntityFactory::createCell($booking['counsellor']['name']),
							    WriterEntityFactory::createCell($booking['package']['package_name']),
							    WriterEntityFactory::createCell($booking['booking_date']),
							    WriterEntityFactory::createCell(($booking['payment_detail']['amount'])),
							];
							$singleRow = WriterEntityFactory::createRow($cells);
							$writer->addRow($singleRow); 
					   }
			       }
			       $total =[
					    WriterEntityFactory::createCell($totalCost),
					];
					$total_revenue = WriterEntityFactory::createRow($total);
					$writer->addRow($total_revenue);
			    }    
			     else if($from_date != "")
		           {
			        $bookings = Booking::with('payment_detail','counsellor','user','package')->where('booking_date','>=',$from_date)->get();
			        foreach ($bookings->unique('payment_id') as $key => $booking) 
			        {
			       	   $totalCost += $booking['payment_detail']['amount']/100;
			       	   if($booking['payment_detail']['balance_transaction'] == "")
			       	   {
				       	   	$cells = [
							    WriterEntityFactory::createCell("txn_1Ixm1456y009ofleXwVCW2EAdmin"),
							    WriterEntityFactory::createCell($booking['counsellor']['name']),
							    WriterEntityFactory::createCell($booking['package']['package_name']),
							    WriterEntityFactory::createCell($booking['booking_date']),
							    WriterEntityFactory::createCell("0.00"),
							   
							];
							$singleRow = WriterEntityFactory::createRow($cells);
							$writer->addRow($singleRow);
			       	    }
			       	   else
			       	   {
			       	     $cells = [
						    WriterEntityFactory::createCell($booking['payment_detail']['balance_transaction']),
						    WriterEntityFactory::createCell($booking['counsellor']['name']),
						    WriterEntityFactory::createCell($booking['package']['package_name']),
						    WriterEntityFactory::createCell($booking['booking_date']),
						    WriterEntityFactory::createCell(($booking['payment_detail']['amount'])),
						   
							];
							$singleRow = WriterEntityFactory::createRow($cells);
							$writer->addRow($singleRow);
						} 
			       }
			       $total =[
					    WriterEntityFactory::createCell($totalCost),
					];
					$total_revenue = WriterEntityFactory::createRow($total);
					$writer->addRow($total_revenue);
			    }   
			    else if($to_date != "")
		             {
                    echo "<script>";
				    echo "alert('Please select from date');";
				    echo "</script>";

			    }  
			   else
			   {
			   	 $bookings = Booking::with('payment_detail','counsellor','user','package')->get();
			        foreach ($bookings as $key => $booking) 
			        {
			       	   $totalCost += $booking['payment_detail']['amount']/100;
			       	   if($booking['payment_detail']['balance_transaction'] == "")
			       	   {
			       	   	$cells = [
						    WriterEntityFactory::createCell("txn_1Ixm1456y009ofleXwVCW2EAdmin"),
						    WriterEntityFactory::createCell($booking['counsellor']['name']),
						    WriterEntityFactory::createCell($booking['package']['package_name']),
						    WriterEntityFactory::createCell($booking['booking_date']),
						    WriterEntityFactory::createCell("0.00"),
						   
						];
						$singleRow = WriterEntityFactory::createRow($cells);
						$writer->addRow($singleRow);
			       	   }
			       	   else
			       	   {
			       	   $cells = [
						    WriterEntityFactory::createCell($booking['payment_detail']['balance_transaction']),
						    WriterEntityFactory::createCell($booking['counsellor']['name']),
						    WriterEntityFactory::createCell($booking['package']['package_name']),
						    WriterEntityFactory::createCell($booking['booking_date']),
						    WriterEntityFactory::createCell(($booking['payment_detail']['amount'])),
						   
						];
						$singleRow = WriterEntityFactory::createRow($cells);
						$writer->addRow($singleRow); 
					}
			       }
			       $total =[
					    WriterEntityFactory::createCell($totalCost),
					];
					$total_revenue = WriterEntityFactory::createRow($total);
					$writer->addRow($total_revenue);
			   }  
					$writer->close();
		      		exit();

           
	    }
}
