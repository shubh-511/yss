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
              <form method="get" url="{{('/listings')}}" enctype="multipart/form-data">
              
                <div class="col-md-12">
                <div class="col-md-5">
                  <label>Listing Name</label>
                  <input type="text" class="form-control" name="listing_name" placeholder="Search by Listing name">
                </div>
                 <div class="col-md-5">
                  <label>Created By</label>
                  <input type="text" class="form-control" name="email" placeholder="Search created by">
                </div>
                
                <br>
                <div class="col-md-2">
                  <input type="submit" class="btn btn-primary" value="Filter">
                </div>
                </div>
              </form>
              <br>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <table id="table" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="check_all_checkbox"></th>
                      <th>Listing Name</th>
                      <th>Phone</th>
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
                          <th><input type="checkbox" class='sub_chk' data-id="{{$listing->id}}" name="listing_id[]"></th>
                          <td>{{ $listing->listing_name ?? ''}}</td>
                          <td>{{ $listing->phone ?? ''}}</td>
                          <td>{{ $listing->listing_category->category_name ?? ''}}</td>
                          <td>{{ $listing->user->email ?? ''}}</td>
                          <td>{{ date('j F, Y', strtotime($listing->created_at)) }}</td>
                          <td>
                            <a href="javascript:" onclick="update_status('{{ $listing->id}}',{{abs($listing->status-1)}})">
                              <span class="label  @if($listing->status!='0') {{'label-success'}} @else {{'label-warning'}} @endif">
                                @if($listing->status=='1') {{'Enabled'}} @else {{'Disabled'}} @endif 
                              </span>
                            </a>
                          </td>
                          <td>
                            <!-- <a data-toggle="modal" data-target="#myModal<?php echo $listing->id?>"><i class="fa fa-desktop" title="View Listing"></i></a> -->
                            <a href="{{url('login/listings/detail',[$listing->id])}}"><i class="fa fa-desktop" title="View Listing"></i></a>
                            <!-- <button type="button" class="btn btn-info btn-lg" >Open Modal</button> -->
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
              <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{($listings->currentpage()-1)*$listings->perpage()+1}} to {{$listings->currentpage()*$listings->perpage()}}
    of  {{$listings->total()}} entries</div>
            </div>
            <div class="col-sm-7">{{ $listings->links() }}</div>
          </div>
        
      </div>

      <!-- /.box -->
    </div>
  </div>
</div>
@endsection

<script>
 function update_status(id,value)
      {     
        $.ajax({
          type: 'GET',
          data: {'id':id,'value':value},
          url: "../login/listingStatus",
          success: function(result){
            //alert( 'Update Action Completed.');
            location.reload();
            
          }});
      }
</script>