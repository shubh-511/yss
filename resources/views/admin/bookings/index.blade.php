@extends('admin.layouts.app')

@section('content')

<div class="row">
  <div class="col-xs-12">
    <!-- Default box -->
      <div class="box">
          <div class="box-header">
            
             <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4">
                    <div class="form-group">
                        
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4">
                    <div class="form-group">
                       
                    </div>
                </div>
                
            </div>
          </form>
          <div class="row ">
          <div class="col-md-12">
            <ul class="pagination pagination-sm" style="margin: 0 0 5px 0;">
            
                
            </ul>
          </div>
        </div>
          </div>
          <div class="box-body">
            <div class="row">
                <!-- <div class="col-md-6">
                  <label class="control-label nopadding col-sm-3 " for="inputEmail">Bulk
                    Action </label>
                  <div class="col-sm-3 nopadding">
                    <select name="bulkaction" id="bulkaction" class="form-control bulk_action" data-url="">
                      <option value="-1">Select</option>
                      <option value="0">Inactive</option>
                      <option value="1">Active</option>
                      <option value="2">Delete</option>
                    </select>
                  </div>
                </div> -->

                 <div class="col-md-6">
                  <h3 class="control-label nopadding col-sm-3 " for="inputEmail">Search</h3>
                  
                </div>
              <form method="get" url="{{('/bookings')}}" enctype="multipart/form-data">
              <div class="col-md-12">
                <div class="col-md-3">
                  <label>User Name/Counsellor Name</label>
                  <input type="text" class="form-control" name="name" placeholder="Search by User name or Counsellor Name">
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
                <br>
                <div class="col-md-3">
                  <input type="submit" class="btn btn-primary" value="Filter">
                </div>
                </div>
              </form>
              <!--  <div class="col-md-12">
                  <div class="col-md-3">
                  <label>Action</label>
                 <select name="action" class="form-control" id="action">
                    <option disabled selected value>Bulk Action</option>
                    <option value="confirm">Booking Confirmed</option>
                    <option value="failed">Booking Failed</option>
                    <option value="cancel">Booking Cancelled</option>
                    <option value="delete">Delete</option>
                  </select>
              </div>
              <br>
              <div class="col-md-3">
                  <input type="submit" class="btn btn-primary" value="Apply" onclick="myFunction()">
                </div>
              </div> -->
           
                <br>
                <div class="col-md-6">
                  <h3 class="control-label nopadding col-sm-3 " for="inputEmail">Bookings</h3>
                  
                </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <table id="table" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="check_all_checkbox"></th>
                      <th>Counsellor Name</th>
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
                            <a data-toggle="modal" data-target="#myModal<?php echo $booking->id?>"><i class="fa fa-desktop" title="View Details"></i></a> |
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
                                <h4 class="modal-title">Appointment/Payment Details</h4>
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
                                          <label>Counsellor name</label>
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
                                      <label style="border-bottom: 1px solid #c3bcbc;
                                      margin-top: 8px;">Payment Details</label>
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
            </div>
          </div>
          <div class="row">
            <div class="col-sm-5">
              <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{($bookings->currentpage()-1)*$bookings->perpage()+1}} to {{$bookings->currentpage()*$bookings->perpage()}}
    of  {{$bookings->total()}} entries</div>
            </div>
            <div class="col-sm-7">{{ $bookings->links() }}</div>
          </div>
        
      </div>

      <!-- /.box -->
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
</script>