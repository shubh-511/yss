@extends('admin.layouts.app')

@section('content')

<div class="row">
  <div class="col-xs-12">
    <!-- Default box -->
      <div class="box">
		<div class="box-header">
			<div class="pull-left">
				<h3 class="box-title">Listings</h3>
			</div>
		</div>

          <div class="box-body">
            <div class="row">
                
                <div class="col-md-6">
                  <h3 class="control-label nopadding col-sm-3 " for="inputEmail">Search</h3>
                  
                </div>

              <form method="get" url="{{('/listings')}}" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-12">
                <div class="col-md-5">
                  <label>Listing Name</label>
                  <input type="text" class="form-control" name="listing_name" placeholder="Search by Listing name">
                </div>
                 <div class="col-md-5">
                  <label>Created By</label>
                  <input type="text" class="form-control" name="email" placeholder="Search created by">
                </div>
				
                <div class="col-md-2">
				           <label>&nbsp;</label>
                  <input type="submit" class="btn btn-primary" value="Filter">
                </div>
                </div>
              </div>
              </form>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-5">
				        <div class="form-group">
                  <label>Bulk Action</label>
                 <select name="action" class="form-control listing-action" id="listdel">
                   <option value="enable">Enabled</option>
                   <option value="disable">Disable</option>
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
               <div class="col-md-3">
                </div>
                <div class="col-md-3">
                </div>
                <div class="col-md-3">
                </div>
                <div class="col-md-3">
                  <input type="submit" class="btn btn-primary" onclick="downloadListing()" value="Download Listing">
                </div>
        
              </div>
            </div>
                <table id="table" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="check_all_checkbox"></th>
                      <th>Listing Name</th>
                      <th>Category</th>
                      <th>Created By</th>
                      <th>Created Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @forelse($listings as $listing)
                        <tr id='booking{{$listing->id}}'>
                          <th><input type="checkbox" class='sub_chk' value="{{$listing->id}}" data-id="{{$listing->id}}" name="listing_id[]"></th>
                          <td>{{ $listing->listing_name ?? ''}}</td>

                          <td>{{ $listing->category->category_name ?? ''}}</td>

                          <td>{{ $listing->user->email ?? ''}}</td>
                          <td style="width: 12rem; word-break: break-word;">{{ date('j F, Y', strtotime($listing->created_at)) }}</td>
                          <td>
                             <a href="javascript:" class="Update" onclick="update_status('{{ $listing->id}}',{{abs($listing->status-1)}})">
                              <span class="label  @if($listing->status!='0') {{'label-success'}} @else {{'label-warning'}} @endif">
                                @if($listing->status=='1') {{'Enabled'}} @else {{'Disabled'}} @endif 
                              </span>
                            </a>
                          </td>
                          <td>
                            <!-- <a data-toggle="modal" data-target="#myModal<?php echo $listing->id?>"><i class="fa fa-desktop" title="View Listing"></i></a> -->
                            <a href="{{url('login/listings/detail',[$listing->id])}}"><i class="fa fa-desktop" title="View Listing"></i></a>
                            <a class="fa fa-edit" href="{{ url('login/counsellors/list/listedit',$listing->id) }}" title="ListEdit"></a>

                            <!-- <button type="button" class="btn btn-info btn-lg" >Open Modal</button> -->
                          </td>
                                                    
                        </tr>

                      @empty
                          <tr>
                          <td colspan="3" class="text-center">No records found</td>
                        </tr>
                      @endforelse
                  </tbody>
              </table><div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{($listings->currentpage()-1)*$listings->perpage()+1}} to {{$listings->currentpage()*$listings->perpage()}}
			  of  {{$listings->total()}} entries</div>
			  <div class="text-center">{{ $listings->links() }}</div>
      </div>

      <!-- /.box -->
    </div>
  </div>
</div>
@endsection

<script type="text/javascript" src='https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.3.min.js'></script>
<script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/js/bootstrap.min.js'></script>
<link rel="stylesheet" media="screen" href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/css/bootstrap.min.css' />

<center>
    <a class="Update" href=""><i class="ace-icon fa fa-pencil-square-o"></i>|</a>
</center>
<div id="MyPopup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;</button>
            </div>
            <div class="modal-body">
              <textarea id="msg" value="" name="message" class="md-textarea form-control" rows="3" required></textarea>
              <input type="hidden" name="id" id="id" value="">

            </div>
            <div class="modal-footer">
                <button name="send" type="submit" class="btn btn-primary" onclick="SendMessage()">Send</button>
            </div>
        </div>
    </div>
</div>

<script>
 function update_status(id,value)
      {  
      if(value == "0")
      {
          $("#MyPopup").modal("show");
          $("#id").val(id);

      }
      else
      {
        $.ajax({
          type: 'GET',
          data: {'id':id,'value':value},
          url: "../login/listingStatus",
           success: function(result){
            alert( 'Update Action Completed.');
            location.reload();
            
           }});
      }
      }
    function SendMessage()
    {
      var msg = $("#msg").val();
      var id= $("#id").val();
      var value="0";
      $.ajax({
        type: 'GET',
        data: {'msg':msg,'id':id,'value':value},
        url: "../login/reject/listingStatus",
         success: function(result){
          alert( 'Update Action Completed.');
          location.reload();
        }});
    }
</script>
 <script>
    function myFunction() {
  
  var urlLike = '{{ url('login/listings/bulk') }}';
  var action = $("#listdel").val();
  var multiple_id = [];    
      $('input:checkbox[name="listing_id[]"]:checked').each(function() {
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
                  window.location.href = '{{ url('login/listings') }}';
                  
                }

            });
}
function downloadListing()
{
  var searchParams = new URLSearchParams(window.location.search);
  let listing_name = searchParams.get('listing_name');
  let email = searchParams.get('email');
  var urlLike = '{{ url('login/download/listing') }}';
       $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
    
                type: 'GET',
                url: urlLike,
                data: {listing_name:listing_name,email:email},
                 xhrFields: {
                responseType: 'blob'
            },
             success: function(response)
                {
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "Listing-Report.xlsx";
                link.click()
                  
                }
               
            });
     
  
}
</script>
@push('select2')
<script>
$(".listing-action").select2({
  tags: false
});
</script>
@endpush