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
                      <th>Counsellor Name</th>
                      <th>User Name</th>
                      <th>Booking Date</th>
                      <th>Slot</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @forelse($bookings as $booking)
                        <tr id='booking{{$booking->id}}'>
                          <th><input type="checkbox" class='sub_chk' data-id="{{$booking->id}}" name="user_id[]"></th>
                          <td>{{ $booking->counsellor->name}}</td>
                          <td>{{ $booking->user->name}}</td>
                          <td>{{ $booking->package->package_name}}</td>
                          <td>{{ $booking->slot}}</td>
                          
                          
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
              <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{($bookings->currentpage()-1)*$bookings->perpage()+1}} to {{$bookings->currentpage()*$bookings->perpage()}}
    of  {{$bookings->total()}} entries</div>
            </div>
            <div class="col-sm-7">{{ $bookings->links() }}</div>
          </div>
        
      </div>

      <!-- /.box -->
    </div>
  </div>
</div>
@endsection

