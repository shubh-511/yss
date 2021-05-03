@extends('admin.layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2></h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/tickets') }}"> Back</a>
        </div>
    </div>
</div>


@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif

<div class="row">
    <div class="col-xs-12">
    <!-- Default box -->

            <div class="box-header">
              
                <div class="modal-content">
                  <div class="modal-header">
                    
                    <h4 class="modal-title">Ticket Details</h4>
                  </div>
                  <div class="modal-body">
                    

                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Ticket ID</label>
                              <label class="form-control">#{{ $ticket->id}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>User name</label>
                              <label class="form-control">{{ $ticket->user->name ?? ''}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Booking ID</label>
                              <label class="form-control">#{{ $ticket->booking_id}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Reason for cancellation</label>
                              <textarea style="resize: vertical;" class="form-control">{{ $ticket->cancel_reason}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Booking Details:</label>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-top: 8px;">
                            <div class="form-group">
                              <label>Counsellor name</label>
                              <label class="form-control">{{ $ticket->booking->counsellor->name ?? ''}}</label>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-top: 8px;">
                            <div class="form-group">
                              <label>Package</label>
                              <label class="form-control">{{ $ticket->booking->package->package_name}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Package amount</label>
                              <label class="form-control">{{ $ticket->booking->package->amount}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Appointment date</label>
                              <label class="form-control"><td>{{ date('j F, Y', strtotime($ticket->booking->booking_date)) }}</td></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Time slot</label>
                              <label class="form-control"><td>{{ $ticket->booking->counsellor_timezone_slot }}</td></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Ticket status</label>
                                <label class="form-control">
                                    <a title="Click to proceed for refund" href="{{url('login/tickets/refund-ticket',[$ticket->id])}}">
                                      <span class="label  @if($ticket->status!='0') {{'label-success'}} @else {{'label-warning'}} @endif">
                                        @if($ticket->status=='1') {{'Refund Initiated'}} @else {{'Pending'}} @endif 
                                      </span>
                                    </a>
                                </label>
                            </div>
                        </div>
                       
              
            
                        @if($ticket->status == 1)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Refund Details:</label>
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top: 8px;">
                                <div class="form-group">
                                  <label>Refunded amount</label>
                                  <label class="form-control">{{ ($ticket->booking->payment_detail->amount_refunded/100) ?? ''}}</label>
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top: 8px;">
                                <div class="form-group">
                                  <label>Refund processed on</label>
                                  <label class="form-control">{{ date('j F, Y', strtotime($ticket->updated_at)) }}</label>
                                </div>
                            </div>
                        @endif

                  </div>

                  </div>
                 
                </div>

                          

            </div>
        
    </div>
</div>


@endsection
<script type="text/javascript">
    function update_status(id,value)
    {     
        if(confirm("Are you sure you want to proceed with the refund?"))
        {
            $.ajax({
            type: 'GET',
            data: {'id':id,'value':value},
            url: "../../tickets/updateTicketStatus",
            success: function(result){
              //alert( 'Update Action Completed.');
              location.reload();

            }});
        }
      
    }
</script>