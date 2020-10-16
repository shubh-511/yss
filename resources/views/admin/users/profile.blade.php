@extends('admin.layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Profile</h2>
        </div>
        
    </div>
</div>



<form method="post" action="{{url('login/profile/update')}}">
    @csrf
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" class="form-control" value="{{$user->name}}" name="name" required placeholder="Name"> 
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <label>Email:</label>
            <input type="email" class="form-control" value="{{$user->email}}" disabled name="email" required placeholder="Email">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <label>First Name:</label>
            <input type="text" class="form-control" value="{{$user->first_name}}" name="first_name" placeholder="First Name">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <label>Middle Name:</label>
            <input type="text" class="form-control" value="{{$user->middle_name}}" name="middle_name" placeholder="Middle Name">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <label>Last Name:</label>
            <input type="text" class="form-control" value="{{$user->last_name}}" name="last_name" placeholder="Last Name">
        </div>
    </div>
    
     
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</div>
</form>



@endsection