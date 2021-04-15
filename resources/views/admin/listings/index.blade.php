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
                <!-- <div class="col-md-6">
                  <label class="control-label nopadding col-sm-3 " for="inputEmail">Bulk
                    Action </label>
                  <div class="col-sm-3 nopadding">
                    <select name="bulkaction" id="bulkaction" class="form-control bulk_action" data-url="">
                      <option value="-1">Select</option>
                      <option value="0">Inactive</option>
                      <option value="1">Active</option>
                      <option value="2">Delete</option>
                    </select>
                  </div>
                </div> -->
                <div class="col-md-6">
                  <!--<a href="" class="btn btnblack btn-mini plain create_list_margin pull-right"><i class="fa fa-plus-circle icon-white"></i> Create Artist</a>-->
                </div>
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
                      <th>Region</th>
                      <th>Label</th>
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
                          <td>{{ $listing->listing_label->label_name ?? ''}}</td>
                          <td>{{ $listing->listing_region->region_name ?? ''}}</td>
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
                            <a data-toggle="modal" data-target="#myModal<?php echo $listing->id?>"><i class="fa fa-desktop" title="View Listing"></i></a>
                            <!-- <button type="button" class="btn btn-info btn-lg" >Open Modal</button> -->
                          </td>
                                                    
                        </tr>

                        <div id="myModal<?php echo $listing->id?>" class="modal fade" role="dialog">
                          <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Listing Details</h4>
                              </div>
                              <div class="modal-body">
                                

                                <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Listing Name</label>
                                          <input type="text" class="form-control" name="listing_name" value="{{ $listing->listing_name ?? ''}}">
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Location</label>
                                          <input type="text" class="form-control" name="location" value="{{ $listing->location ?? ''}}">
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Phone</label>
                                          <input type="text" class="form-control" name="phone" value="{{ $listing->phone ?? ''}}">
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Contact email/url</label>
                                          <input type="text" class="form-control" name="contact_email_or_url">
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="form-group">
                                          <label>Description</label>
                                          <textarea style="resize: vertical;" class="form-control" name="description">{{ $listing->description ?? ''}}</textarea>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Category</label>
                                          <input type="text" class="form-control" name="listing_category" value="{{ $listing->listing_category->category_name ?? ''}}">
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Label</label>
                                          <input type="text" class="form-control" name="listing_label" value="{{ $listing->listing_label->label_name ?? ''}}">
                                      </div>

                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Region</label>
                                          <input type="text" class="form-control" name="listing_region" value="{{ $listing->listing_region->region_name ?? ''}}">
                                      </div>

                                  </div>
                                  
                                  
                                   
                                  <!-- <div class="col-md-6">
                                      <button type="submit" class="btn btn-primary">Send</button>
                                  </div> -->
                              </div>



                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </div>

                          </div>
                        </div>

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