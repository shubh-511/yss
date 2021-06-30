@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2></h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/role/privilege') }}"> Back</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
    <!-- Default box -->

            <div class="box-header">
              
                <div class="modal-content">
                  <div class="modal-header">
                    
                    <h4 class="modal-title">Create Role Privilege</h4>
                  </div>
                  <div class="modal-body">
                    
                    <form action="{{url('login/save/privilege')}}" method="post">
                    <div class="row">
                        @csrf
                        <div class="col-md-6">
                            <label>User Role</label>
                            <select class="form-control privilege" name="role">
                              <option value="">Choose Role</option>
                              @foreach($role_data as $role)
                              <option value="{{$role->id}}">{{$role->role}}</option>
                              @endforeach
                            </select>
                          </div>
                       <div class="row">
                        <div class="col-sm-12">
                            <table id="table" class="table table-bordered table-striped">
                              <thead>
                                <tr>
                                  <th>Module</th>
                                  <th>All <input type="checkbox" id="check_all_checkbox"></th>
                                </tr>
                              </thead>
                               <tbody>
                                @foreach($module_data as $module)
                                  <tr id='module{{$module->id}}'>
                                    <td>{{ $module->module_name}}</td>
                                    <th><input type="checkbox" class='sub_chk bulk-action-btn' data-id="{{$module->id}}" value="{{$module->id}}" name="module[]"></th>
                                  </tr>
                               @endforeach
                            </tbody>
                        </table>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <button style="float: right;" name="addrole" type="submit" class="btn btn-primary ">Save</button>
                        </div>
                      </div>
               </div>
        </form>
      </div>
     </div>
   </div>
</div>
</div>
@endsection
@push('select2')
<script>
$(".privilege").select2({
  tags: false,
  placeholder: "Choose Role"
});
  </script>
  @endpush