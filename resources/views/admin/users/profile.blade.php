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
				<div class="pull-left">
					<h3 class="box-title">Profile</h3>
				</div>
			</div>
			<div class="box-body">
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
								  <input type="Password" class="form-control"  name="old_pass" placeholder="Old Password" id="myInputOld">
								  <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password" onclick="myFunctionOld()"></span>
								 </div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							  <div class="form-group">
								<label>New Password:</label>
								  <input type="Password" class="form-control"  name="new_pass" placeholder="New Password" id="myInputNew">
								  <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password" onclick="myFunctionNew()"></span>
								 </div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							  <div class="form-group">
								<label>Confirm Password:</label>
								  <input type="Password" class="form-control"  name="conf_pass" placeholder="Confirm Password" id="myInput">
								  <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"  onclick="myFunction()"></span>
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

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  function myFunction() {
    var x = document.getElementById("myInput");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
   function myFunctionNew() {
    var x = document.getElementById("myInputNew");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
  function myFunctionOld() {
    var x = document.getElementById("myInputOld");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
  
</script>