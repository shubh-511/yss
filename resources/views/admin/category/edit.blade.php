@extends('admin.layouts.app')


@section('content')
<!-- <div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Category</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/category') }}"> Back</a>
        </div>
    </div>
</div> -->

<div class="box-header">
    <div class="pull-left">
        <h3 class="box-title">Edit Category</h3>
    </div>
    <div class="pull-right">
        <a class="btn btn-primary" href="{{ url('login/category') }}"> Back</a>
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
    <div class="box">
            <div class="box-header send--noti">
                <h3 class="box-title">Edit Category: #{{$category_edit->id}}</h3>
            </div>

                <div class="box-body">
                    <form action="{{url('login/category/update',[$category_edit->id])}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Category Name:</label>
                                    <input type="text" class="form-control" value="{{$category_edit->category_name}}" name="category_name" placeholder="Category Name"> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Status:</label>
                                   <select name="status" class="form-control edit-category">
                                        @if($category_edit->status==1)
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                        @else
                                        <option value="1">Active</option>
                                        <option value="0" selected>Inactive</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                              <div class="form-group">
                                <button name="editcounsellor" type="submit" class="btn btn-primary ">Update</button>
                              </div>
                            </div>
                            
                           
                        </div>
                        </form>
                <!-- <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Category: #{{$category_edit->id}}</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{url('login/category/update',[$category_edit->id])}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Category Name:</label>
                                    <input type="text" class="form-control" value="{{$category_edit->category_name}}" name="category_name" placeholder="Category Name"> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Status:</label>
                                   <select name="status" class="form-control ">
                                        @if($category_edit->status==1)
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                        @else
                                        <option value="1">Active</option>
                                        <option value="0" selected>Inactive</option>
                                        @endif
                                    </select>
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
                </div> -->
</div>
</div>
            </div>
    </div> 


@endsection
@push('select2')
<script>
$(".edit-category").select2({
  tags: false
});
</script>
@endpush