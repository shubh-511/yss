@extends('admin.layouts.app')

@section('content')

<div class="row">
  <div class="col-xs-12">
    <!-- Default box -->
      <div class="box">
          <!-- <div class="box-header">
            
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
          </div> -->
          <div class="box-body">
            <div class="box-header">
              <div class="pull-left">
                <h3 class="box-title"for="inputEmail">Module</h3>
              </div>
              <div class="pull-right">
                <a href="{{url('login/module/create')}}" class="btn btnblack btn-mini plain create_list_margin pull-right"><i class="fa fa-plus-circle icon-white"></i> Add Module</a>
              </div>
            </div>

            
            <!-- <div class="row">
                <div class="col-md-6">
                  <h3 class="control-label nopadding col-sm-3 " for="inputEmail">Module</h3>
                </div>
                 <div class="col-md-6">
                  <a href="{{url('login/module/create')}}" class="btn btnblack btn-mini plain create_list_margin pull-right"><i class="fa fa-plus-circle icon-white"></i> Add Module</a>
                </div>
            </div> -->
            <div class="row">
              <div class="col-sm-12">
                <table id="table" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Module ID</th>
                      <th>Module Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @forelse($module_data as $module)
                        <tr id='module{{$module->id}}'>
                          <td>#{{ $module->id}}</td>                
                          <td>{{ $module->module_name ?? ''}}</td>  
                           <td>
                            <a class="fa fa-edit" href="{{url('login/module/edit',[$module->id])}}"></a>
                           
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
              <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{($module_data->currentpage()-1)*$module_data->perpage()+1}} to {{$module_data->currentpage()*$module_data->perpage()}}
              of  {{$module_data->total()}} entries</div>
            </div>
            <div class="col-sm-7">{{ $module_data->links() }}</div>
          </div>
        
      </div>

      <!-- /.box -->
    </div>
  </div>
</div>
@endsection
