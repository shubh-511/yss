@extends('admin.layouts.app')
@section('content')
<!-- <div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2></h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/role') }}"> Back</a>
        </div>
    </div>
</div> -->

<div class="box-header">
    <div class="pull-left">
        <h3 class="box-title">Create Role</h3>
    </div>
    <div class="pull-right">
        <a class="btn btn-primary" href="{{ url('login/role') }}"> Back</a>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
    <!-- Default box -->
    <div class="box">
      <!-- <div class="box-header">
          <h3 class="box-title">Create Role</h3>
        </div> -->
            <div class="box-body">
              <form action="{{url('login/role/save')}}" method="post">
                    <div class="row">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Role</label>
                              <input type="text" name="role" class="form-control @error('role') is-invalid @enderror" value="{{old('role')}}">
                              @error('role')
                                <p style="color:red">{{ $errors->first('role') }}</p>
                              @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Name</label>
                              <input type="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}">
                              @error('name')
                                <p style="color:red">{{ $errors->first('name') }}</p>
                              @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <button name="addrole" type="submit" class="btn btn-primary ">Save</button>
                          </div>
                        </div>
                        
                  </div>
                </form>
                <!-- <div class="modal-content">
                  <div class="modal-header">
                    
                    <h4 class="modal-title">Create Role</h4>
                  </div>
                  <div class="modal-body">
                    
                    <form action="{{url('login/role/save')}}" method="post">
                    <div class="row">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Role</label>
                              <input type="text" name="role" class="form-control @error('role') is-invalid @enderror" value="{{old('role')}}">
                              @error('role')
                                <p style="color:red">{{ $errors->first('role') }}</p>
                              @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Name</label>
                              <input type="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}">
                              @error('name')
                                <p style="color:red">{{ $errors->first('name') }}</p>
                              @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <button style="float: right;" name="addrole" type="submit" class="btn btn-primary ">Save</button>
                          </div>
                        </div>
                        
                  </div>
                </form>
              </div>
             </div> -->
           </div>
        </div>
</div>
@endsection