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
                <div class="col-xs-4 col-sm-4 col-md-4 text-center">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="" class="btn btn-primary btnblack">Clear</a>
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
                </div>
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
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Featured</th>
                      <th>Display Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @forelse($users as $user)
                        <tr id='artist{{$user->id}}'>
                          <th><input type="checkbox" class='sub_chk' data-id="{{$user->id}}" name="artist_id[]"></th>
                          <td>{{ $user->name}}</td>
                          
                          <td>{{ $user->email}}</td>
                          <td>@if($user->role_id == 2){{'Counsellor'}} @elseif($user->role_id == 3){{'Consumer'}} @else {{'Admin'}} @endif</td>
                          <!--<td>{{ ($user->display_status == 1) ? 'Active' : 'Inactive' }}</td>-->
                          <td>
                            <a href="" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                           
                             
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

