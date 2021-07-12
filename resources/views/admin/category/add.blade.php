@extends('admin.layouts.app')
@section('content')
<!-- <div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2></h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/category') }}"> Back</a>
        </div>
    </div>
</div> -->

<div class="box-header">
    <div class="pull-left">
        <h3 class="box-title">Create Category</h3>
    </div>
    <div class="pull-right">
        <a class="btn btn-primary" href="{{ url('login/category') }}"> Back</a>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
    <!-- Default box -->
          <div class="box">
            <div class="box-body">
              <form action="{{url('login/category/save')}}" method="post">
                    <div class="row">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Category name</label>
                              <input type="text" name="category_name" class="form-control @error('category_name') is-invalid @enderror" value="{{old('category_name')}}" required>
                              @error('category_name')
                                <p style="color:red">{{ $errors->first('category_name') }}</p>
                              @enderror
                            </div>
                        </div>
                          <div class="col-md-6">
                                <div class="form-group">
                                  <label>Status:</label>
                                   <select name="status" class="form-control add-category">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                       
                        <div class="col-md-12">
                          <div class="form-group">
                            <button name="addcategory" type="submit" class="btn btn-primary ">Save</button>
                          </div>
                        </div>
                        
                  </div>
                </form>
                <!-- <div class="modal-content">
                  <div class="modal-header">
                    
                    <h4 class="modal-title">Create Category</h4>
                  </div>
                  <div class="modal-body">
                    
                    <form action="{{url('login/category/save')}}" method="post">
                    <div class="row">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Category name</label>
                              <input type="text" name="category_name" class="form-control @error('category_name') is-invalid @enderror" value="{{old('category_name')}}" required>
                              @error('category_name')
                                <p style="color:red">{{ $errors->first('category_name') }}</p>
                              @enderror
                            </div>
                        </div>
                          <div class="col-md-6">
                                <div class="form-group">
                                  <label>Status:</label>
                                   <select name="status" class="form-control add-category">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
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
                 
                </div> -->
              </div>
            </div>
            </div>
        </div>
</div>
@endsection
@push('select2')
<script type="text/javascript">
$(".add-category").select2({
  tags: false
});
</script>
@endpush