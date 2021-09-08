@extends('admin.layouts.app')

@section('content')

<!-- <div class="box-header">
    <div class="pull-left">
        <h3 class="box-title" for="inputEmail">Roles</h3>
    </div>
    <div class="pull-right">
        <a href="{{url('login/role/create')}}" class="btn btnblack btn-mini plain create_list_margin pull-right"><i class="fa fa-plus-circle icon-white"></i> Add Role</a>
    </div>
</div> -->

<div class="row">
  <div class="col-xs-12">
    <!-- Default box -->
      <div class="box">
          <div class="box-body">
            <div class="box-header">
				<div class="pull-left">
					<h3 class="box-title">Roles</h3>
				</div>
				<div class="pull-right">
					<a href="{{url('login/role/create')}}" class="btn btnblack btn-mini plain create_list_margin pull-right"><i class="fa fa-plus-circle icon-white"></i> Add Role</a>
				</div>
			</div>
            <div class="row">
              <div class="col-sm-12">
                <table id="table" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Role ID</th>
                      <th>Role</th>
                     
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @forelse($role_data as $role)
                        <tr id='role{{$role->id}}'>
                          <td>#{{ $role->id}}</td>
                          <td>{{ $role->role ?? ''}}</td>                  
                           
                           <td>
                            <a class="fa fa-edit" href="{{url('login/role/edit',[$role->id])}}"></a>
                           
                          </td>
                                                    
                        </tr>

                      @empty
                          <tr>
                          <td colspan="3" class="text-center">No records found</td>
                        </tr>
                      @endforelse
                  </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-5">
              <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{($role_data->currentpage()-1)*$role_data->perpage()+1}} to {{$role_data->currentpage()*$role_data->perpage()}}
              of  {{$role_data->total()}} entries</div>
            </div>
            <div class="col-sm-7">{{ $role_data->links() }}</div>
          </div>
        
      </div>

      <!-- /.box -->
    </div>
  </div>
</div>
@endsection
