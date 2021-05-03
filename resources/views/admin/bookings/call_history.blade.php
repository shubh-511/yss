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
                    
                    <h4 class="modal-title">Call History for booking ID #{{$bookingId}}</h4>
                  </div>
                  <div class="modal-body">
                    

       
                  <?php $i = 1; ?>
                  @foreach($callLogs as $callLog)
                    <div class="row">
                      <div class="col-md-12" style="margin-bottom: 15px;">Attempt: {{$i}}</div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                              <label>Call initiated by:</label>
                              <label class="form-control">{{ $callLog->initiated_by->name ?? ''}}</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                              <label>Call pick by:</label>
                              <label class="form-control">{{ $callLog->picked_by->name ?? '--'}}</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                              <label>Call disconnected by:</label>
                              <label class="form-control">{{ $callLog->cutted_by->name ?? ''}}</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                              <label>Call status:</label>
                              <label class="form-control">{{ $callLog->status}}</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                              <label>Call duration:</label>
                              <label class="form-control">{{ $callLog->call_duration}}</label>
                            </div>
                        </div>
                       
                    </div>
                    <?php $i++; ?>
                   @endforeach 

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