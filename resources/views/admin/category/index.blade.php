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
                 <div class="col-md-12">
                  <div class="col-md-6">
                  <label>Bulk Action</label>
                 <select name="action" class="form-control" id="catdel">
                   <option value="active">Active</option>
                   <option value="inactive">InActive</option>
                    <option value="delete">Delete</option>
                  </select>
              </div>
               <br>
              <div class="col-md-2">
                  <input type="submit" class="btn btn-primary" value="Apply" onclick="myFunction()">
                </div>
              </div>
              <div class="col-md-6">
                  <h3 class="control-label nopadding col-sm-3 " for="inputEmail">Category</h3>
                  
                </div>
                <div class="col-md-6">
                  <a href="{{url('login/category/create')}}" class="btn btnblack btn-mini plain create_list_margin pull-right"><i class="fa fa-plus-circle icon-white"></i> Add Category</a>
                </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <table id="table" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="check_all_checkbox"></th>
                      <th>Category ID</th>
                      <th>Category Name</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @forelse($listing_category as $category)
                        <tr id='booking{{$category->id}}'>
                          <th><input type="checkbox" class='sub_chk' value="{{$category->id}}" data-id="{{$category->id}}" name="user_id[]"></th>
                          <td>#{{ $category->id}}</td>
                          <td>{{ $category->category_name}}</td>
                         <td>
                           
                              <span class="label  @if($category->status!='0') {{'label-success'}} @else {{'label-warning'}} @endif">
                                @if($category->status=='1') {{'Active'}} @else {{'InActive'}} @endif 
                              </span>
                          
                          </td>
                                                    
                        <td>
                            
                            <a class="fa fa-edit" href="{{ url('login/category/edit',$category->id) }}" title="Edit"></a>
                             <a class="fa fa-trash" onClick="deletecat({{$category->id}})" title="Delete"></a>
                           
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
              <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{($listing_category->currentpage()-1)*$listing_category->perpage()+1}} to {{$listing_category->currentpage()*$listing_category->perpage()}}
    of  {{$listing_category->total()}} entries</div>
            </div>
            <div class="col-sm-7">{{ $listing_category->links() }}</div>
          </div>
        
      </div>

      <!-- /.box -->
    </div>
  </div>

@endsection
<script type="text/javascript">
 function deletecat(id)
      {
        if (confirm("Are you sure you want to delete?") == true) {
        $.ajax({
        url:"{{url('login/category/destroy')}}",
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
  
  var urlLike = '{{ url('login/category/bulk') }}';
  var action = $("#catdel").val();
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
                  window.location.href = '{{ url('login/category') }}';
                  
                }

            });
}
</script>