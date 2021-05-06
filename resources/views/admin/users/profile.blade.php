@extends('admin.layouts.app')
@section('content')
<style type="text/css">
    .field-icon {
  float: right;
  margin-left: -25px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}
</style>
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Profile</h2>
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
            <div class="box-header">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Profile</h4>
                    </div>
                    <div class="modal-body">
                       <form method="post" action="{{url('login/profile/update')}}">
                       @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Name:</label>
                                  <input type="text" class="form-control" value="{{$user->name}}" name="name" required placeholder="Name">  
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Email:</label>
                                <input type="email" class="form-control" value="{{$user->email}}" name="email" required placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>First Name:</label>
                                    <input type="text" class="form-control" value="{{$user->first_name}}" name="first_name" placeholder="First Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Middle Name:</label>
                                    <input type="text" class="form-control" value="{{$user->middle_name}}" name="middle_name" placeholder="Middle Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Last Name:</label>
                                    <input type="text" class="form-control" value="{{$user->last_name}}" name="last_name" placeholder="Last Name">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                  <div class="form-group">
                                    <label>Old Password:</label>
                                      <input type="Password" class="form-control"  name="old_pass" placeholder="Old Password">
                                      <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                     </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <div class="form-group">
                                    <label>New Password:</label>
                                      <input type="Password" class="form-control"  name="new_pass" placeholder="New Password">
                                      <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                     </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <div class="form-group">
                                    <label>Confirm Password:</label>
                                      <input type="Password" class="form-control"  name="conf_pass" placeholder="Confirm Password">
                                      <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                  </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update</button>
                              </div>
                            </div>
                            
                           
                        </div>
                        </form>
                    </div>
                </div>

            </div>
    </div>            
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $(".toggle-password").click(function() {
  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});

</script>