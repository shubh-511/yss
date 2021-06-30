@extends('admin.layouts.app')

@section('content')

<div class="row">
  <div class="col-xs-12">
    <!-- Default box -->
      <div class="box">
          <div class="box-header">
            
             <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4">
                    <div class="form-group">
                        
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4">
                    <div class="form-group">
                       
                    </div>
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
                  <h3 class="control-label nopadding col-sm-3 " for="inputEmail">Search</h3>
                  
                </div>
              <form method="get" url="{{('/users')}}" enctype="multipart/form-data">
              <div class="col-md-12">
                <div class="col-md-3">
                  <label>Name Or Email</label>
                  <input type="text" class="form-control" name="email" placeholder="Search by name or email">
                </div>
                <div class="col-md-3">
                  <label>Status</label>
                 <select name="status" class="form-control user-status">
                    <option value="">select</option>
                    <option value="1">Active</option>
                    <option value="0">Account Disabled</option>
                    <option value="3">Pending for verification</option>
                  </select>
                </div>
                <br>
                <div class="col-md-3">
                  <input type="submit" class="btn btn-primary" value="Filter">
                </div>
                </div>
              </form>
               <div class="col-md-12">
                  <div class="col-md-3">
                  <label>Action</label>
                 <select name="action" class="form-control user-action" id="action">
                    <option disabled selected value>Bulk Action</option>
                    <option value="active">Active</option>
                    <option value="disabled">Account Disabled</option>
                    <option value="verification">Pending for verification</option>
                    <option value="delete">Delete</option>
                  </select>
              </div>
              <br>
              <div class="col-md-3">
                  <input type="submit" class="btn btn-primary" value="Apply" onclick="myFunction()">
                </div>
              </div>
           
              
                <br>
                <div class="col-md-6">
                  <h3 class="control-label nopadding col-sm-3 " for="inputEmail">Users</h3>
                  
                </div>
                <div class="col-md-6">
                  <a href="{{url('login/user/create')}}" class="btn btnblack btn-mini plain create_list_margin pull-right"><i class="fa fa-plus-circle icon-white"></i> Add user</a>
                </div>
            </div>
            
            <div class="row">
              <div class="col-sm-12">
                <table id="table" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="check_all_checkbox"></th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @forelse($users as $user)
                        <tr id='user{{$user->id}}'>
                          <th><input type="checkbox" class='sub_chk bulk-action-btn' data-id="{{$user->id}}" value="{{$user->id}}" name="user_id[]"></th>
                          <td>{{ $user->name}}</td>
                          
                          <td>{{ $user->email}}</td>
                          <td><a href="javascript:void();">
                              <span class="label  @if($user->account_enabled == '1' || $user->account_enabled == '2') {{'label-success'}} @else {{'label-warning'}} @endif">
                                @if($user->account_enabled == '1' || $user->account_enabled == '2') {{'Active'}} @elseif($user->account_enabled == '0') {{'Account Disabled'}} @elseif($user->account_enabled == '3') {{'Pending for verification'}} @endif
                              </span>
                            </a></td>
                           <td>
                             <a class="fa fa-desktop" href="{{ url('login/users/show',$user->id) }}" title="View"></a>
                             <a class="fa fa-edit" href="{{ url('login/users/edit',$user->id) }}" title="Edit"></a>
                             <a class="fa fa-trash" onClick="deleteUser({{$user->id}})" title="Delete"></a>
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
              <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{($users->currentpage()-1)*$users->perpage()+1}} to {{$users->currentpage()*$users->perpage()}}
    of  {{$users->total()}} entries</div>
            </div>
            <div class="col-sm-7">{{ $users->links() }}</div>
          </div>
        
      </div>

      <!-- /.box -->
    </div>
  </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function myFunction() {
  
  var urlLike = '{{ url('login/users/bulk') }}';
  var action = $("#action").val();
  var multiple_id = [];    
      $('input:checkbox[name="user_id[]"]:checked').each(function() {
      multiple_id.push($(this).val());
    });
      if(multiple_id == "")
      {
        alert('Please Checked At List one Checkbox');
      }
   $.ajax({
     headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
                type: 'GET',
                url: urlLike,
                data: {id: multiple_id,action:action},
                dataType: 'json',
                success: function(response)
                {
                  alert("Action Activate successfully");
                  window.location.href = '{{ url('login/users') }}';
                  
                }

            });
}
</script>
@push('select2')
<script>
$(".user-status").select2({
  tags: false
});
$(".user-action").select2({
  tags: false
});
</script>
@endpush