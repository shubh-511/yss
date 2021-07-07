@extends('admin.layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2></h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/users') }}"> Back</a>
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
                        <h4 class="modal-title">User Details: #{{$user->id}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Name:</label>
                                    <label class="form-control">{{$user->name}}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Email:</label>
                                    <label class="form-control">{{$user->email}}</label>
                                </div>
                            </div>
                           
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Timezone:</label>
                                    <label class="form-control">{{$user->timezone}}</label>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>





                

            </div>
    </div>
</div>


@endsection