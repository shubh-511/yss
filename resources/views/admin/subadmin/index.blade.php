@extends('admin.layouts.app')
@section('content')
<div class="row">
  <div class="col-xs-12">
    <!-- Default box -->
      <div class="box">
			<div class="box-header">
				<div class="pull-left">
					<h3 class="box-title">Sub Admin</h3>                  
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
					  <div class="form-group">
						<label>Bulk Action</label>
						<select name="action" class="form-control bulk-label" id="sub-admin">
							<option value="delete">Delete</option>
						</select>
					  </div>
					</div>
					<div class="col-md-2">
					  <div class="form-group">
						<label>&nbsp;</label>
						<input type="submit" class="btn btn-primary" value="Apply" onclick="myFunction()">
					  </div>
					</div>
				</div>
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
						@forelse($subAdmin as $sub_admin_user)
						<tr id='{{$sub_admin_user->id}}'>
							<th><input type="checkbox" class='sub_chk' value="{{$sub_admin_user->id}}" data-id="{{$sub_admin_user->id}}" name="user_id[]"></th>
							<td>#{{ $sub_admin_user->name}}</td>
							<td>{{ $sub_admin_user->email}}</td>
							<td><a href="javascript:void();">
                              <span class="label  @if($sub_admin_user->account_enabled == '1' || $sub_admin_user->account_enabled == '2') {{'label-success'}} @else {{'label-warning'}} @endif">
                                @if($sub_admin_user->account_enabled == '1' || $sub_admin_user->account_enabled == '2') {{'Active'}} @elseif($sub_admin_user->account_enabled == '0') {{'Account Disabled'}} @elseif($sub_admin_user->account_enabled == '3') {{'Pending for verification'}} @endif
                              </span>
                            </a></td>
                           <td>
							<td>
							    <a class="fa fa-edit" href="{{ url('login/sub/admin/edit',$sub_admin_user->id) }}" title="Edit"></a>
								<a class="fa fa-trash" onClick="deleteSubAdmin({{$sub_admin_user->id}})" title="Delete"></a>
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="3" class="text-center">No records found</td>
						</tr>
						@endforelse
					</tbody>
				</table>
				<div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{($subAdmin->currentpage()-1)*$subAdmin->perpage()+1}} to {{$subAdmin->currentpage()*$subAdmin->perpage()}}
				of  {{$subAdmin->total()}} entries</div>
				<div class="text-center">{{ $subAdmin->links() }}</div>
			</div>        
		</div>
		<!-- /.box -->
    </div>
</div>

@endsection
<script type="text/javascript">
 function deleteSubAdmin(id)
      {
        if (confirm("Are you sure you want to delete?") == true) {
        $.ajax({
        url:"{{url('login/sub/admin/destroy')}}",
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
    <script>
    function myFunction() {
  var urlLike = '{{ url('login/sub/admin/bulk') }}';
  var action = $("#sub-admin").val();
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
                  window.location.href = '{{ url('login/sub/admin') }}';
                  
                }

            });
}
</script>
@push('select2')
<script>
$(".bulk-label").select2({
  tags: false
});
</script>
@endpush