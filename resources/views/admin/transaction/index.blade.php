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
              <form method="get" url="{{('/transaction')}}" enctype="multipart/form-data">
              <div class="col-md-12">
                <div class="col-md-3">
                  <label>Counsellor Name</label>
                  <input type="text" class="form-control"  value="{{old('name')}}" name="name" placeholder="Search by Counsellor Name">
                </div>
                <div class="col-md-3">
                  <label>Date</label>
                 <select name="date" class="form-control">
                    <option value="">select</option>
                    <option value="0">Today</option>
                    <option value="7">Past 7 days</option>
                    <option value="30">Past 30 days</option>
                    <option value="90">Past 90 days</option>
                  </select>
                </div>
                <br>
                <div class="col-md-3">
                  <input type="submit" class="btn btn-primary" value="Filter">
                </div>
                </div>
              </form>
                <br>
                <div class="col-md-12">
                   <div class="col-md-3">
                  <h3 class="control-label nopadding col-sm-3 " for="inputEmail">Bookings</h3>
                </div>
                 <div class="col-md-6">
                 </div>
                  <div class="col-md-3">
                  <input type="submit" class="btn btn-primary" onclick="Myfunction()" value="Download Report">
                </div>
                </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <table id="table" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Serial No</th>
                      <th>Counsellor Name</th>
                      <th>Package Name</th>
                      <th>Date Of Booking</th>
                      <th>Total Revenue</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php  $i=1; @endphp
                      @forelse($bookings as $booking)
                        <tr id='booking{{$booking->id}}'>
                          <td>@php echo $i; @endphp</td>
                          <td>{{ $booking->counsellor->name ?? ''}}</td>
                          <td>{{ $booking->package->package_name ?? ''}}</td>
                          <td>{{ date('j F, Y', strtotime($booking->booking_date)) }}</td>   
                          <td>€{{ $booking->payment_detail->amount/100 ?? ''}}</td>                       
                        </tr>
                       @php $i++; @endphp
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
<script type="text/javascript">
function Myfunction()
{
  var searchParams = new URLSearchParams(window.location.search);
  let name = searchParams.get('name');
  let date = searchParams.get('date');
  var urlLike = '{{ url('login/transaction/download') }}';
  if(searchParams == "" || name == "" || date =="")
      {
        alert('Please search with counselor name and date');
      }
      else
      {
       $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
    
                type: 'GET',
                url: urlLike,
                data: {name: name,date:date},
                 xhrFields: {
                responseType: 'blob'
            },
                success: function(response)
                {
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "revenuereport.pdf";
                link.click()
                  
                }

            });
     }
  
}
  </script>