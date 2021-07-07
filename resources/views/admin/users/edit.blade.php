@extends('admin.layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit User</h2>
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
                        <h4 class="modal-title">Edit User: #{{$user->id}}</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{url('login/users/update',[$user->id])}}" method="post">
                        @csrf
                        <div class="row">
                             <!-- <div class="col-md-6">
                            <label>User Role</label>
                            <select class="form-control role_edit" name="role">
                              <option value="">Select Role</option>
                              @foreach($role_data as $role)
                              <option value="{{$role->id}}" {{ ( $role->id == $user->role_id) ? 'selected' : '' }}>{{$role->role}}</option>
                              @endforeach
                            </select>
                          </div>
                           <div class="col-md-6">
                            <label>User Module</label>
                            <select class="form-control module_edit" name="module">
                              <option value="">Select Module</option>
                              @foreach($module_data as $module)
                              <option value="{{$module->id}}" {{ ( $module->id == $user->module_id) ? 'selected' : '' }}>{{$module->module_name}}</option>
                              @endforeach
                            </select>
                          </div> -->
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
@push('select2')

<script type="text/javascript">
  $(".role_edit").select2({
  tags: false,
  placeholder: "Select Role"
});
  $(".module_edit").select2({
  tags: false,
  placeholder: "Select Module"
});
</script>
@endpush