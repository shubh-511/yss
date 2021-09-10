@extends('admin.layouts.app')

@section('content')

<div class="row">
	<div class="col-xs-12">
		<!-- Default box -->
		<div class="box">
			<div class="box-header">
				<div class="pull-left">
					<h3 class="box-title">Bookings</h3>
				</div>
			</div>
			<div class="box-body">
				<div class="">
					<form method="get" url="{{('/bookings')}}" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-3">
								<label>User Name/Coach Name</label>
								<input type="text" class="form-control" name="name" placeholder="Search by User name or Coach Name">
							</div>
							<div class="col-md-3">
								<label>Appointment Date</label>
								<input type="date" class="form-control" name="booking_date" placeholder="Search By Appointment Date">
							</div>
							<div class="col-md-3">
								<label>Status</label>
								<select name="status" class="form-control">
									<option value="">select</option>
									<option value="1">Booking confirmed</option>
									<option value="0">Booking Failed</option>
									<option value="4">Booking cancelled</option>
								</select>
							</div>
							<div class="col-md-3">
								<label>&nbsp;</label>
								<input type="submit" class="btn btn-primary" value="Filter">
							</div>
						</div>
					</form>
					<div class="col-md-3">
					</div>
					<div class="col-md-3">
					</div>
					<div class="col-md-3">
					</div>
					<!--<div class="col-md-3">
                                            <input type="submit" class="btn btn-primary" onclick="downloadBooking()" value="Download Booking">
                                         </div>-->
					<table id="table" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="check_all_checkbox"></th>
								<th>Coach Name</th>
								<th>User Name</th>
								<th>Package Name</th>
								<th>Appointment Date</th>
								<th>Slot</th>
								<th>Booking status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@forelse($bookings as $booking)
							<tr id='booking{{$booking->id}}'>
								<th><input type="checkbox" class='sub_chk' value="{{$booking->id}}" data-id="{{$booking->id}}" name="user_id[]"></th>
								<td>{{ $booking->counsellor->name ?? ''}}</td>
								<td>{{ $booking->user->name ?? ''}}</td>
								<td>{{ $booking->package->package_name ?? ''}}</td>
								<td>{{ date('j F, Y', strtotime($booking->booking_date)) }}</td>
								<td>{{ $booking->slot}}</td>
								<td><a href="javascript:void();">
								<span class="label  @if($booking->status == '1') {{'label-success'}} @else {{'label-warning'}} @endif">
								@if($booking->status == '1') {{'Booking confirmed'}} @elseif($booking->status == '0') {{'Booking failed'}} @elseif($booking->status == '4') {{'Booking cancelled'}} @endif
								</span>
								</a></td>
												
								<td>
								<a href="javascript:void(0)" data-toggle="modal" data-target="#myModal<?php echo $booking->id?>"><i class="fa fa-desktop" title="View Details"></i></a> |
								<a href="{{url('login/call-history',[$booking->id])}}"><i class="fa fa-history" aria-hidden="true" title="view call history"></i></a>
								<a href="{{url('login/download/report',[$booking->id])}}"><i class="fa fa-Download" aria-hidden="true" title="download report"></i></a>
								</td>											
							</tr>

							<div id="myModal<?php echo $booking->id?>" class="modal fade" role="dialog">
							  <div class="modal-dialog">

								<!-- Modal content-->
								<div class="modal-content">
								  <div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h3 class="modal-title" style="font-weight: 600;">Appointment/Payment Details</h3>
								  </div>
								  <div class="modal-body">
									

									<div class="row">
									  <div class="col-md-6">
										  <div class="form-group">
											  <label>User name</label>
											  <label class="form-control">{{ $booking->user->name ?? ''}}</label>
										  </div>
									  </div>
									  <div class="col-md-6">
										  <div class="form-group">
											  <label>Coach name</label>
											  <label class="form-control">{{ $booking->counsellor->name ?? ''}}</label>
										  </div>
									  </div>
									  <div class="col-md-6">
										  <div class="form-group">
											  <label>Package name</label>
											  <label class="form-control">{{ $booking->package->package_name ?? ''}}</label>
										  </div>
									  </div>
									  
									  <div class="col-md-6">
										  <div class="form-group">
											  <label>Appointment Date & Time</label>
											  <label class="form-control">{{ $booking->booking_date ?? ''}}{{' '}}{{ $booking->slot ?? ''}}</label>
										  </div>
									  </div>  
									  <div class="col-md-6">
										  <div class="form-group">
											  <label>Status</label>
											  <label class="form-control">@if($booking->status == '0'){{'Booking failed'}} @elseif($booking->status == '1') {{'Booking success'}} @elseif($booking->status == '4') {{'Booking cancelled'}} @endif</label>
										  </div>
									  </div>

									  <div class="col-md-12">
										<div class="form-group">
										  <h3 class="modal-title" style="border-bottom: 1px solid #c3bcbc; margin-top: 8px; font-weight: 600;">Payment Details</h3>
										</div>
									  </div>
									  @if($booking->created_by == 1)
									  <div class="col-md-6" style="margin-top: 10px;">
										  <div class="form-group">
											  <label>Charge ID</label>
											  <label class="form-control">{{$booking->payment_detail->charge_id ?? ''}}</label>
										  </div>
									  </div>
									  <div class="col-md-6" style="margin-top: 10px;">
										  <div class="form-group">
											  <label>Payment intent</label>
											  <label class="form-control">{{$booking->payment_detail->payment_intent ?? ''}}</label>
										  </div>
									  </div>
									  <div class="col-md-6">
										  <div class="form-group">
											  <label>Payment method</label>
											  <label class="form-control">{{$booking->payment_detail->payment_method ?? ''}}</label>
										  </div>
									  </div>
									  <div class="col-md-6">
										  <div class="form-group">
											  <label>Amount paid</label>
											  <label class="form-control">&#163;{{($booking->payment_detail->amount ?? '0')/100}}</label>                           
										 </div>
									  </div>
									  <div class="col-md-6">
										  <div class="form-group">
											  <label>Refunded amount</label>
											  <label class="form-control">{{ $booking->payment_detail->amount_refunded ?? '0' }}</label>
										  </div>
									  </div>
									  <div class="col-md-6">
										  <div class="form-group">
											  <label>Status</label>
											  <label class="form-control">{{ $booking->payment_detail->status ?? '' }}</label>
										  </div>
									  </div>
									  <div class="col-md-6">
										  <div class="form-group">
											  <label>Payment reciept</label>
											  <label class="form-control"><a href="{{ $booking->payment_detail->receipt_url ?? '' }}" target="_blank">View reciept</a></label>
										  </div>
									  </div>
									  @else
									  <div class="col-md-12">
									  <p>This Booking was made by YSS admin hence no payment details found for this booking..</p>
									  </div>
									  @endif
									  
								  </div>

								  </div>
								  <div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								  </div>
								</div>

							  </div>
							</div>

						  @empty
							  <tr>
							  <td colspan="3" class="text-center">No records found</td>
							</tr>
						  @endforelse
						</tbody>
					</table>
					<div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{($bookings->currentpage()-1)*$bookings->perpage()+1}} to {{$bookings->currentpage()*$bookings->perpage()}}
					of  {{$bookings->total()}} entries</div>
					<div class="text-center">{{ $bookings->appends(Request::all())->links() }}
                                       </div>
                                </div>
			</div>
		</div>
	</div>
</div>
              
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function myFunction() {
  
  var urlLike = '{{ url('login/bookings/bulk') }}';
  var action = $("#action").val();
  var multiple_id = [];    
      $('input:checkbox[name="user_id[]"]:checked').each(function() {
      multiple_id.push($(this).val());
    });
      if(multiple_id == "")
      {
        alert('Please Checked At List one Checkbox');
      }
   $.ajax({
     headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
                type: 'GET',
                url: urlLike,
                data: {id: multiple_id,action:action},
                dataType: 'json',
                success: function(response)
                {
                  alert("Action Activate successfully");
                  window.location.href = '{{ url('login/bookings') }}';
                  
                }

            });
}
function downloadBooking()
{
  var searchParams = new URLSearchParams(window.location.search);
  let name = searchParams.get('name');
  let status = searchParams.get('status');
  let booking_date = searchParams.get('booking_date');
  var urlLike = '{{ url('login/download/bookings') }}';
       $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
    
                type: 'GET',
                url: urlLike,
                data: {name:name,status:status,booking_date:booking_date},
                 xhrFields: {
                responseType: 'blob'
            },
             success: function(response)
                {
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "Booking-Report.xlsx";
                link.click()
                  
                }
               
            });
     
  
}
</script>
@push('select2')
<script>
$(".booking-status").select2({
  tags: false
});
</script>
@endpush