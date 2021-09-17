@extends('admin.layouts.app')
@section('content')
<div class="box-header">
    <div class="pull-left">
        <h3 class="box-title">Send Notification</h3>
    </div>
    <div class="pull-right">
        <a class="btn btn-primary" href="{{ url('login/dashboard') }}">Back</a>
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
            <div class="box-header send--noti">
                <form action="{{url('login/send')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group desc">
                                <div class="form-radios-keys">
                                   <label>
                                    <input type="checkbox"  id="check_id" value="0" name="radio" />Select All</label>
                                  <label>
                                    <input type="checkbox"   id="check_id" value="2" name="radio" />Select All Coach</label>
                                  <label>
                                    <input type="checkbox"  id="check_id" value="1" name="radio" />Select All User</label>
                                </div>
                                <select name="user[]" class="form-control" id="notification-tags" multiple>
                                </select>
                            </div>
                            <div class="form-group asc">
                                <div class="form-radios-keys">
                                     <label>
                                    <input type="checkbox"  id="check_id" value="0" name="radio" />Select All</label>
                                  <label>
                                    <input type="checkbox"   id="check_id" value="2" name="radio" />Select All Coach</label>
                                  <label>
                                    <input type="checkbox"  id="check_id" value="1" name="radio" />Select All User</label>
                                </div>
                                <select name="user[]" class="form-control" id="notification" multiple>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}     -      {{$user->email}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div>
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
                        <div class="col-md-12 text-right">
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
  $("input:checkbox").on('click', function() {
   var $box = $(this);
   if ($box.is(":checked")) {
    var group = "input:checkbox[name='" + $box.attr("name") + "']";
    $(group).prop("checked", false);
    $box.prop("checked", true);
    $('input:checkbox[name=radio]').each(function() 
    {    
      if($(this).is(':checked') == true)
      {
         $("div.desc").show();
         $("div.asc").hide();
         var radioValue=$(this).val();
         var urlLike = '{{ url('login/sendnoti') }}';
         $.ajax({
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
                type: 'GET',
                url: urlLike,
                data: {radioValue: radioValue},
                dataType: 'json',
                success:function(res)
                {     
                    $("#notification-tags").empty();
                    if(res)
                    {
                        $.each(res,function(key,value){
                            $('#notification-tags').append($("<option/>", {
                               value: value,
                               text: key
                            }));
                        });
                        $("#notification-tags > option").prop("selected","selected");
                    }
                }

            });
        }
      });
  } else {
    $box.prop("checked", false);
    $("div.desc").hide();
    $("div.asc").show();
  }
});
</script>
<script type="text/javascript">
    $("#notification-tags").select2({
  tags: false
});
$("#notification").select2({
  tags: false
});
$(document).ready(function()
    {
     $('.ckeditor').ckeditor();
    });
  $(document).ready(function() {
    $("div.desc").hide();
});
</script>
@endpush