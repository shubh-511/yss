@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2></h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/region') }}"> Back</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
    <!-- Default box -->

            <div class="box-header">
              
                <div class="modal-content">
                  <div class="modal-header">
                    
                    <h4 class="modal-title">Create Region</h4>
                  </div>
                  <div class="modal-body">
                    
                    <form action="{{url('login/region/save')}}" method="post">
                    <div class="row">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Region name</label>
                              <input type="text" name="region_name" class="form-control @error('region_name') is-invalid @enderror" value="{{old('region_name')}}" required>
                              @error('region_name')
                                <p style="color:red">{{ $errors->first('region_name') }}</p>
                              @enderror
                            </div>
                        </div>
                          <div class="col-md-6">
                                   <label>Status:</label>
                                   <select name="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">InActive</option>
                                    </select>
                                
                            </div>
                       
                        <div class="col-md-12">
                          <div class="form-group">
                            <button style="float: right;" name="addcategory" type="submit" class="btn btn-primary ">Save</button>
                          </div>
                        </div>
                        
                  </div>
                </form>

                  </div>

                      <div class="modal-footer">
                        
                      </div>
                 
                </div>
            </div>
        </div>
</div>
@endsection