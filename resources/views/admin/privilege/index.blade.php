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
          </form>
          <div class="row ">
          <div class="col-md-12">
            <ul class="pagination pagination-sm" style="margin: 0 0 5px 0;">
            
                
            </ul>
          </div>
        </div>
          </div>
          <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                  <h3 class="control-label nopadding col-sm-3 " for="inputEmail">Role</h3>
                </div>
                 <div class="col-md-6">
                  <a href="{{url('login/create/privilege')}}" class="btn btnblack btn-mini plain create_list_margin pull-right"><i class="fa fa-plus-circle icon-white"></i> Add Role Privilege</a>
                </div>
            </div>
            <form action="{{url('login/update/privilege')}}" method="post">
              <div class="row">
                  @csrf
              <div class="col-sm-12">
                <table id="table" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Role</th>
                      <th>Module</th>
                      <th>Action</th>
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
                      <td>
                        <a class="fa fa-edit" href="{{ url('login/edit/previlege',$role->id) }}" title="Edit"></a>
                       <a class="fa fa-trash" onClick="deletePrevilege({{$role->id}})" title="Delete"></a>
                      </td>
                    </tr>
                    @endif
                    @endforeach
                    @endforeach
                    
                  </tbody>
              </table>
              </div>
          </div>
        </form>
      </div>


      <!-- /.box -->
    </div>
</div>
@endsection
<script type="text/javascript">
  function deletePrevilege(id)
      {
        if (confirm("Are you sure you want to delete?") == true) {
        $.ajax({
        url:"{{url('login/previlege/destroy')}}",
        type:'get',
        data:{'id':id,'_token':'{{ csrf_token() }}'},
        success: function(path){
        location.reload();
        }
        });
        } else {
        return false;
        }
      }
</script>