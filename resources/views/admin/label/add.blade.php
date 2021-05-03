@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2></h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/label') }}"> Back</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
    <!-- Default box -->

            <div class="box-header">
              
                <div class="modal-content">
                  <div class="modal-header">
                    
                    <h4 class="modal-title">Create Label</h4>
                  </div>
                  <div class="modal-body">
                    
                    <form action="{{url('login/label/save')}}" method="post">
                    <div class="row">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Label name</label>
                              <input type="text" name="label_name" class="form-control @error('label_name') is-invalid @enderror" value="{{old('label_name')}}" required>
                              @error('label_name')
                                <p style="color:red">{{ $errors->first('label_name') }}</p>
                              @enderror
                            </div>
                        </div>
                          <div class="col-md-6">
                                <div class="form-group">
                                  <label>Status:</label>
                                   <select name="status" class="form-control ">
                                        <option value="1">Active</option>
                                        <option value="0">InActive</option>
                                    </select>
                                </div>
                            </div>
                       
                        <div class="col-md-12">
                          <div class="form-group">
                            <button style="float: right;" name="addlabel" type="submit" class="btn btn-primary ">Save</button>
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