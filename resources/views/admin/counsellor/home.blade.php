@extends('admin.layouts.app')

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Dashbord</h3>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
        <div class="inner">
        <h3>{{$bookingCount}}</h3>

        <p>Total Bookings</p>
        </div>
        <div class="icon">
        <i class="fa fa-ticket"></i>
        </div>
        </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
             <div id="bookingChart"></div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
<!-- Booking Chart -->
<script type="text/javascript">
    var users =  <?php echo json_encode($bookings) ?>;
    var month =  <?php echo json_encode($month_name) ?>;

   alert(month);
    Highcharts.chart('bookingChart', {
        title: {
            text: 'Current month Bookings'
        },
        chart: {
            type: 'area'
        },
        subtitle: {
            text: ''
        },
         xAxis: {
            categories: [month],
        },
        yAxis: {
            title: {
                text: 'Current month Bookings'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{
            name: 'Current month Bookings',
            data: users,
            
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
});
</script>
<!-- End Booking Chart -->
    
@endsection
