@extends('admin.layouts.app')
@section('content')


<div class="row">
  <div class="col-xs-12">
    <!-- Default box -->
      <div class="box">
		<div class="box-header">
			<div class="pull-left">
				<h3 class="box-title">Bookings</h3>
			</div>
		</div>
          <div class="box-body">
            <div class="row">
              <form method="get" url="{{('/transaction')}}" enctype="multipart/form-data">
                <div class="row">
              <div class="col-md-12">
                <div class="col-md-3">
                  <label>Counsellor Name</label>
                  <select class="form-control transaction" name="counsellor">
                    <option value="">Select Counsellor</option>
                    @foreach($counsellor_data as $data)
                    <option value="{{$data->id}}">{{$data->name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <label>Date</label>
                 <select name="date" class="form-control transaction-action">
                    <option value="">select</option>
                    <option value="0">Today</option>
                    <option value="7">Past 7 days</option>
                    <option value="30">Past 30 days</option>
                    <option value="90">Past 90 days</option>
                  </select>
                </div>
				
                <div class="col-md-3">
				  <label>&nbsp;</label>
                  <input type="submit" class="btn btn-primary" value="Filter">
                </div>
				<div class="col-md-3 ">
				  <label>&nbsp;</label>
                  <input type="submit" class="btn btn-primary" onclick="Myfunction()" value="Download Report">
                </div>
                </div>
              </div>
              </form>
            </div>
                <table id="table" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Serial No</th>
                      <th>Transaction No</th>
                      <th>Total Revenue</th>
                      <th>Counsellor Name</th>
                      <th>Package Name</th>
                      <th>Date Of Booking</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php  $i=1; @endphp
                      @forelse($bookings->unique('payment_id') as $booking)
                        <tr id='booking{{$booking->id}}'>
                          <td>@php echo $i; @endphp</td>
                           <td>{{$booking->payment_detail->balance_transaction}}</td>
                           <td>€{{ $booking->payment_detail->amount/100 ?? ''}}.00</td>
                          <td>{{ $booking->counsellor->name ?? ''}}</td>
                          <td>{{ $booking->package->package_name ?? ''}}</td>
                          <td>{{ date('j F, Y', strtotime($booking->booking_date)) }}</td>                     
                        </tr>
                       @php $i++; @endphp
                      @empty
                          <tr>
                          <td colspan="3" class="text-center">No records found</td>
                        </tr>
                         
                      @endforelse
                  </tbody>
              </table>
			  <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{($bookings->currentpage()-1)*$bookings->perpage()+1}} to {{$bookings->currentpage()*$bookings->perpage()}}
              of  {{$bookings->total()}} entries</div>
			  <div class="text-center">{{ $bookings->links() }}</div>
      </div>

      <!-- /.box -->
    </div>
  </div>
</div>
@endsection
@push('select2')
<script type="text/javascript">
function Myfunction()
{
  var searchParams = new URLSearchParams(window.location.search);
  let counsellor = searchParams.get('counsellor');
  let date = searchParams.get('date');
  var urlLike = '{{ url('login/transaction/download') }}';
  if(searchParams == "")
      {
       }
      else
      {
       $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
    
                type: 'GET',
                url: urlLike,
                data: {counsellor: counsellor,date:date},
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
$(".transaction").select2({
  tags: false,
  placeholder: "Select Counsellor"
});
$(".transaction-action").select2({
  tags: false
});
  </script>
  @endpush