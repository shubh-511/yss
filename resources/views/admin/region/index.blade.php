@extends('admin.layouts.app')

@section('content')

<div class="row">
  <div class="col-xs-12">
    <!-- Default box -->
		<div class="box">
			<div class="box-header">
				<div class="pull-left">
					<h3 class="box-title">Region</h3>                  
				</div>
				<div class="pull-right">
					<a href="{{url('login/region/create')}}" class="btn btnblack btn-mini plain create_list_margin pull-right"><i class="fa fa-plus-circle icon-white"></i> Add Region</a>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
					  <div class="form-group">
						<label>Bulk Action</label>
						<select name="action" class="form-control bulk-region" id="regdel">
							<option value="active">Active</option>
							<option value="inactive">InActive</option>
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
							<th>Region ID</th>
							<th>Region Name</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@forelse($listing_region as $region)
						<tr id='booking{{$region->id}}'>
							<th><input type="checkbox" class='sub_chk' value="{{$region->id}}" data-id="{{$region->id}}" name="user_id[]"></th>
							<td>#{{ $region->id}}</td>
							<td>{{ $region->region_name}}</td>
							<td>
								<span class="label  @if($region->status!='0') {{'label-success'}} @else {{'label-warning'}} @endif">
								@if($region->status=='1') {{'Active'}} @else {{'InActive'}} @endif 
								</span>
							</td>										
							<td>
								<a class="fa fa-edit" href="{{ url('login/region/edit',$region->id) }}" title="Edit"></a>
								<a class="fa fa-trash" onClick="deletereg({{$region->id}})" title="Delete"></a>
							</td>									
						</tr>
						@empty
					<tr>
					<td colspan="3" class="text-center">No records found</td>
					</tr>
					@endforelse
					</tbody>
				</table>
				<div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{($listing_region->currentpage()-1)*$listing_region->perpage()+1}} to {{$listing_region->currentpage()*$listing_region->perpage()}}
				of  {{$listing_region->total()}} entries</div>
				<div class="text-center">{{ $listing_region->links() }}</div>
			</div>
          </div>        
      </div>
      <!-- /.box -->
    </div>
  </div>

@endsection
<script type="text/javascript">
 function deletereg(id)
      {
        if (confirm("Are you sure you want to delete?") == true) {
        $.ajax({
        url:"{{url('login/region/destroy')}}",
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
  
  var urlLike = '{{ url('login/region/bulk') }}';
  var action = $("#regdel").val();
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
                  window.location.href = '{{ url('login/region') }}';
                  
                }

            });
}
</script>
@push('select2')
<script>
$(".bulk-region").select2({
  tags: false
});
</script>
@endpush