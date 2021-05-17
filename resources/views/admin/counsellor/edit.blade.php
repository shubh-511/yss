@extends('admin.layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2></h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/counsellors') }}"> Back</a>
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
                        <h4 class="modal-title">Edit Counsellor: #{{$user->id}}</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{url('login/counsellors/update',[$user->id])}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Counsellor Type:</label>
                                   <select name="counsellor_type" class="form-control ">
                                       @if($user->counsellor_type==1)
                                        <option value="1" selected>Outside Counsellor</option>
                                        <option value="0">Inside Counsellor</option>
                                        @else
                                        <option value="1">Outside Counsellor</option>
                                        <option value="0" selected>Inside Counsellor</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Name:</label>
                                    <input type="text" class="form-control" value="{{$user->name}}" name="name" required placeholder="Name"> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Email:</label>
                                    <input type="email" class="form-control" value="{{$user->email}}" disabled name="email" required placeholder="Email">
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
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                  <label>Country code:</label>
                                    <label class="form-control">{{$user->country_code}}</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label>Phone:</label>
                                    <label class="form-control">{{$user->phone}}</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <button style="float:right;" name="editcounsellor" type="submit" class="btn btn-primary ">Update</button>
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