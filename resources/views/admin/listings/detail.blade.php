@extends('admin.layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2></h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/listings') }}"> Back</a>
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
                    
                    <h4 class="modal-title">Listing details</h4>
                  </div>
                  <div class="modal-body">
                    

                    <div class="row">
                      
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Listing name:</label>
                              <label class="form-control">{{$listing->listing_name}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Created By:</label>
                              <label class="form-control"><a title="view detail" href="{{url('login/counsellors/show',[$listing->user_id])}}">{{$listing->user->name ?? ''}}</a></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Location:</label>
                              <label class="form-control">{{$listing->location}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Contact Email/URL:</label>
                              <label class="form-control">{{$listing->contact_email_or_url}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Phone:</label>
                              <label class="form-control">{{$listing->phone}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Website:</label>
                              <label class="form-control">{{$listing->website}}</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                              <label>Description:</label>
                              <textarea class="form-control">{{$listing->description}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Category:</label>
                              <label class="form-control">{{$listing->category->category_name}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Region:</label>
                              <label class="form-control">{{$listing->region->region_name}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Label:</label>
                              <label class="form-control">{{$listing->label->label_name}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Status:</label>
                              <label class="form-control">@if($listing->status == 1) {{'Enabled'}} @else {{'Disabled'}} @endif</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Video URL:</label>
                              <label class="form-control">{{$listing->video_url}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Created on:</label>
                              <label class="form-control">{{ date('j F, Y', strtotime($listing->created_at)) }}</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                              <label>Video URL:</label>
                              <label class="form-control">{{$listing->video_url}}</label>
                            </div>
                        </div>
                       
                       
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