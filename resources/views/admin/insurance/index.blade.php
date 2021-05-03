@extends('admin.layouts.app')
@section('content')
<style type="text/css">
	.close {
    color: #F32013; 
    opacity: 1;
}
</style>
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
              <form method="get" url="{{('/insurance')}}" enctype="multipart/form-data">
              
              	<div class="col-md-12">
                <div class="col-md-6">
                  <label>Name Or Email</label>
                  <input type="text" class="form-control" name="name" placeholder="Search by name or email">
                </div>
                
                <br>
                <div class="col-md-2">
                  <input type="submit" class="btn btn-primary" value="Filter">
                </div>
                </div>
              </form>
              <div class="col-md-12">
                  <div class="col-md-6">
                  <label>Bulk Action</label>
                 <select name="action" class="form-control" id="insdel">
                    <option value="delete">Delete</option>
                  </select>
              </div>
               <br>
              <div class="col-md-2">
                  <input type="submit" class="btn btn-primary" value="Apply" onclick="myFunction()">
                </div>
              </div>
          </div>
                <br>
                <div class="col-md-6">
                  <h3 class="control-label nopadding col-sm-3 " for="inputEmail">Insurance</h3>
                  
                </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <table id="table" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="check_all_checkbox"></th>
                      <th>Insurance ID</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Email</th>
                      <th>Insurance No</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @forelse($insurance_data as $insurance)
                        <tr id='insurance{{$insurance->id}}'>
                          <th><input type="checkbox" class='sub_chk' value="{{$insurance->id}}" data-id="{{$insurance->id}}" name="user_id[]"></th>
                          <td>#{{ $insurance->id}}</td>
                          <td>{{ $insurance->first_name}}</td>
                          <td>{{ $insurance->last_name}}</td>
                          
                          <td>{{$insurance->email}}</td>
                          <td>{{$insurance->insurance_no}}</td>                          
                        <td>
                             <a class="fa fa-desktop" data-toggle="modal" data-target="#myModal{{$insurance->id}}"></a>
                             <a class="fa fa-trash" onClick="deleteinsurance({{$insurance->id}})" title="Delete"></a>
                            
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
              <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">      Showing {{($insurance_data->currentpage()-1)*$insurance_data->perpage()+1}} 
              	to {{$insurance_data->currentpage()*$insurance_data->perpage()}}
                of  {{$insurance_data->total()}} entries</div>
            </div>
            <div class="col-sm-7">{{ $insurance_data->links() }}</div>
          </div>
        
      </div>

      <!-- /.box -->
    </div>
  </div>

<div class="container">
  @foreach($insurance_data as $insurance)
  <div class="modal fade" id="myModal{{$insurance->id}}" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <b class="modal-title text-success"><center>User Insurance Detail</center></b>
        </div>
        <div class="modal-body">
           <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Insurance ID</label>
                              <label class="form-control">#{{$insurance->id}}</label>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                              <label>First Fame</label>
                              <label class="form-control">{{ $insurance->first_name}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Last Name</label>
                              <label class="form-control">{{ $insurance->last_name}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Email</label>
                              <label class="form-control">{{ $insurance->email}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Phone No</label>
                              <label class="form-control">{{ $insurance->phone}}</label>
                            </div>
                        </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Insurance Provider</label>
                              <label class="form-control">{{ $insurance->insurance_provider}}</label>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                              <label>Insurance No</label>
                              <label class="form-control">{{ $insurance->insurance_no}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Date</label>
                              <label class="form-control">{{ date('j F, Y', strtotime($insurance->created_at)) }}</label>
                            </div>
                        </div>
                    </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  @endforeach
  
</div>

</body>
</html>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function myFunction() {
  
  var urlLike = '{{ url('login/insurance/bulk') }}';
  var action = $("#insdel").val();
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
                  window.location.href = '{{ url('login/insurance') }}';
                  
                }

            });
}
</script>
<script type="text/javascript">
 function deleteinsurance(id)
      {
        if (confirm("Are you sure you want to delete?") == true) {
        $.ajax({
        url:"{{url('login/insurance/destroy')}}",
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