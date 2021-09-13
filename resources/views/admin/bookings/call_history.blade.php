@extends('admin.layouts.app')


@section('content')


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
		<div class="box">
			<div class="box-header">
				<div class="pull-left">
					<h3 class="box-title">Call History</h3>
				</div>
				<div class="pull-right">
					<a class="btn btn-primary" href="{{ url('login/bookings') }}"> Back</a>
				</div>
			</div>
			<div class="box-body">
				<table id="table" class="table table-bordered table-striped">
	                  <thead>
	                    <tr>
	                      <th>S.No</th>
	                      <th>Caller</th>
	                      <th>Receiver</th>
	                      <th>Call Start Date & Time</th>
	                      <!-- <th>Call End Date & Time</th> -->
	                      <th>Call Duration</th>
	                    </tr>
	                  </thead>
	                  <tbody>   
					  <?php $i = 1; ?>
					  @forelse($callLogs as $callLog) 
					  <tr>
					  	<td>{{$i}}</td>
					  	<td>{{ $callLog->initiated_by->name ?? ''}}</td>
					  	<td>{{ $callLog->picked_by->name ?? ''}}</td>
					  	<td>{{ date("Y-m-d H:i:s", strtotime($callLog->created_at) - $callLog->duration) }}</td>
					  	<!-- <td>{{ $callLog->created_at }}</td> -->
					  	<td>{{ $callLog->call_duration}}</td>
					  	<!-- <td>{{ $callLog->cutted_by->name ?? ''}}</td> -->
					  </tr>
						<?php $i++; ?>
			   			@empty
                          <tr>
                          <td colspan="4" class="text-center">No records found</td>
                        </tr>
                      @endforelse
                  </tbody>
              </table>
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