@extends('admin.layouts.app')
@section('content')
<!-- <div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2></h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/module') }}"> Back</a>
        </div>
    </div>
</div> -->

<div class="box-header">
    <div class="pull-left">
        <h3 class="box-title">Create Module</h3>
    </div>
    <div class="pull-right">
        <a class="btn btn-primary" href="{{ url('login/module') }}"> Back</a>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
    <!-- Default box -->
      <div class="box">
            <div class="box-body">
              <form action="{{url('login/module/save')}}" method="post">
                    <div class="row">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Module Name</label>
                              <input type="module_name" name="module_name" class="form-control @error('module_name') is-invalid @enderror" value="{{old('module_name')}}">
                              @error('module_name')
                                <p style="color:red">{{ $errors->first('module_name') }}</p>
                              @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <button name="addmodule" type="submit" class="btn btn-primary ">Save</button>
                          </div>
                        </div>
                        
                  </div>
                </form>
                <!-- <div class="modal-content">
                  <div class="modal-header">
                    
                    <h4 class="modal-title">Create Module</h4>
                  </div>
                  <div class="modal-body">
                    
                    <form action="{{url('login/module/save')}}" method="post">
                    <div class="row">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Module Name</label>
                              <input type="module_name" name="module_name" class="form-control @error('module_name') is-invalid @enderror" value="{{old('module_name')}}">
                              @error('module_name')
                                <p style="color:red">{{ $errors->first('module_name') }}</p>
                              @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <button name="addmodule" type="submit" class="btn btn-primary ">Save</button>
                          </div>
                        </div>
                        
                  </div>
                </form>
              </div>
             </div> -->
           </div>
         </div>
        </div>
</div>
@endsection