@extends('admin.layouts.app')

@section('content')

<div class="row">
  <div class="col-xs-12">
    <!-- Default box -->
      <div class="box">
          <div class="box-header">
			<div class="pull-left">
				<h3 class="box-title">Tickets</h3>
			</div>
          </div>
          <div class="box-body">
            <div class="row">
              <form method="get" url="{{('/tickets')}}" enctype="multipart/form-data">
              <div class="col-md-12">
				  <div class="row">
					<div class="col-md-3">
					  <label>Username</label>
					  <input type="text" class="form-control" name="name" placeholder="Search by User name">
					</div>
					<div class="col-md-3">
					  <label>Date</label>
					  <input type="date" class="form-control" name="date" placeholder="Search by Date">
					</div>
					 <div class="col-md-3">
					  <label>Status</label>
					 <select name="status" class="form-control">
					  <option value="">select</option>
						<option value="1">Refund Initiated</option>
						<option value="0">Pending</option>
					  </select>
					</div>
					<div class="col-md-3">
					  <label>&nbsp;</label>
					  <input type="submit" class="btn btn-primary" value="Filter">
					</div>
				  </div>
				</div>
              </form>
              <div class="col-md-12">
				<div class="row">
                  <div class="col-md-3">
					<label>Action</label>
					<select name="action" class="form-control" id="action">
						<option disabled selected value>Bulk Action</option>
						<option value="refund">Refund Initiated</option>
						<option value="pending">Pending</option>
						<option value="delete">Delete</option>
					</select>
				  </div>
				  <div class="col-md-3">
					<label>&nbsp;</label>
					<input type="submit" class="btn btn-primary" value="Apply" onclick="myFunction()">
				  </div>
				</div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <table id="table" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="check_all_checkbox"></th>
                      <th>Ticket ID</th>
                      <th>User Name</th>
                      <th>Booking ID</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @forelse($tickets as $ticket)
                        <tr id='booking{{$ticket->id}}'>
                          <th><input type="checkbox" class='sub_chk' value="{{$ticket->id}}" data-id="{{$ticket->id}}" name="user_id[]"></th>
                          <td>#{{ $ticket->id}}</td>
                          <td>{{ $ticket->user->name ?? ''}}</td>
                          <td>#<a href="" title="view booking details">{{ $ticket->booking_id}}</a></td>
                          
                          <td>{{ date('j F, Y', strtotime($ticket->created_at)) }}</td>
                          <td>
                            <a title="Click to proceed for refund" href="{{url('login/tickets/refund-ticket',[$ticket->id])}}">
                              <span class="label  @if($ticket->status!='0') {{'label-success'}} @else {{'label-warning'}} @endif">
                                @if($ticket->status=='1') {{'Refund Initiated'}} @else {{'Pending'}} @endif 
                              </span>
                            </a>
                          </td>
                                                    
                        <td>
                            <a class="fa fa-desktop" href="{{url('login/tickets/detail',[$ticket->id])}}"></a>
                           
                          </td>
                                                    
                        </tr>

                      @empty
                          <tr>
                          <td colspan="7" class="text-center">No records found</td>
                        </tr>
                      @endforelse
                  </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-5">
              <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{($tickets->currentpage()-1)*$tickets->perpage()+1}} to {{$tickets->currentpage()*$tickets->perpage()}}
    of  {{$tickets->total()}} entries</div>
            </div>
            <div class="col-sm-7">{{ $tickets->links() }}</div>
          </div>
        
      </div>

      <!-- /.box -->
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
            url: "tickets/updateTicketStatus",
            success: function(result){
              //alert( 'Update Action Completed.');
              location.reload();

            }});
        }
      
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function myFunction() {
  
  var urlLike = '{{ url('login/tickets/bulk') }}';
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
                  window.location.href = '{{ url('login/tickets') }}';
                  
                }

            });
}
</script>