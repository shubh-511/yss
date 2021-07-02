@extends('admin.layouts.app')

@section('content')

<div class="row">
  <div class="col-xs-12">
    <!-- Default box -->
		<div class="box">
			<div class="box-header">
				<div class="pull-left">
					<h3 class="box-title">Role</h3>
                </div>
				<div class="pull-right">
					<a href="{{url('login/create/privilege')}}" class="btn btnblack btn-mini plain create_list_margin pull-right"><i class="fa fa-plus-circle icon-white"></i> Add Role Privilege</a>
                </div>
			</div>
			<div class="box-body">
				<form action="{{url('login/update/privilege')}}" method="post">
					<div class="row">
						@csrf
						<div class="col-sm-12">
							<table id="table" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Role</th>
										<th>Module</th>
									</tr>
								</thead>
								<tbody>
									@foreach($unique_data_role as $unique_role)
									@foreach($r_role as $role)
									@if($role->id == $unique_role)
									<tr>
										<td>{{$role->name}}</td>
										<td>
											@foreach($r_module as $module)
											@foreach($module_id as $id)
											@if($role->id == $id->role_id && $id->module_id==$module->id)
											<label>{{ $module->module_name}} <input type="checkbox" name="module_id[]" value="{{$module->id}}"{{  ($module->id == $id->module_id ? ' checked' : '') }}></label>
											@endif
											@endforeach
											@endforeach
										</td>
									</tr>
									@endif
									@endforeach
									@endforeach
								</tbody>
							</table>
						</div>
						<!-- <div class="col-md-12">
						<div class="form-group">
						<button style="float: right;" name="addrole" type="submit" class="btn btn-primary ">Update</button>
						</div>
						</div> -->
					</div>
				</form>
			</div>
		</div>

      <!-- /.box -->
    </div>
</div>
@endsection
