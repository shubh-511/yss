@extends('admin.layouts.app')

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Dashbord</h3>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
        <div class="inner">
        <h3>{{$userCount}}</h3>

        <p>Users</p>
        </div>
        <div class="icon">
        <i class="fa fa-user"></i>
        </div>
        <a href="{{url('login/users')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
        </div>
        
        
<!-- ./col -->
</div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->
@endsection
