@extends('admin.layouts.app')
@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Send Notification</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/dashboard') }}"> Back</a>
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
@if(Session::has('err_message'))
<div class="alert alert-danger">
  <strong>{{ Session::get('err_message') }}</strong>
</div>
@endif


<div class="row">
    <div class="col-xs-12">
    <!-- Default box -->
        <div class="box">
            <div class="box-header">
                <form action="{{url('login/send')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select User Or Counsellor:</label><br>
                                <input type="checkbox" id="checkbox" >Select All
                                <select name="user[]" class="form-control" id="notification-tags" multiple>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}     -      {{$user->email}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Notification Title:</label>
                                <input type="text" class="form-control" value="" name="title" required placeholder="Title">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Notification Body:</label>
                                <textarea style="resize: vertical;" class="form-control ckeditor" name="body"></textarea>
                            </div>
                        </div>
                        
                        
                         
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
@push('select2')

<script type="text/javascript">
    $("#notification-tags").select2({
  tags: false
});
$("#checkbox").click(function()
{
    if($("#checkbox").is(':checked') ){
        $("#notification-tags > option").prop("selected","selected");
    }else{
        $('#notification-tags> option').prop('selected', false);
     }
});

$(document).ready(function()
    {
     $('.ckeditor').ckeditor();
    });
</script>
@endpush